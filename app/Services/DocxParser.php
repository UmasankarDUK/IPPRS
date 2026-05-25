<?php

namespace App\Services;

use ZipArchive;
use XMLReader;

class DocxParser
{
    /**
     * Parse a .docx file and return an array of structured sections (headings and HTML content).
     *
     * @param string $filePath
     * @return array
     */
    public function parse(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [];
        }

        $stream = $zip->getStream('word/document.xml');
        if (!$stream) {
            $zip->close();
            return [];
        }

        // Save stream to a temp file for XMLReader (XMLReader requires a file path or URL for stable streaming)
        $tempFile = tempnam(sys_get_temp_dir(), 'docx_xml_');
        $out = fopen($tempFile, 'w');
        while (!feof($stream)) {
            fwrite($out, fread($stream, 16384));
        }
        fclose($out);
        fclose($stream);
        $zip->close();

        $reader = new XMLReader();
        if (!$reader->open($tempFile)) {
            unlink($tempFile);
            return [];
        }

        $sections = [];
        $currentHeading = 'General Information';
        $currentContent = [];
        
        $inParagraph = false;
        $inPPr = false;
        $inText = false;
        $currentParagraphText = '';
        $currentParagraphStyle = '';

        while ($reader->read()) {
            if ($reader->nodeType == XMLReader::ELEMENT) {
                if ($reader->name === 'w:p') {
                    $inParagraph = true;
                    $currentParagraphText = '';
                    $currentParagraphStyle = '';
                } elseif ($reader->name === 'w:pPr') {
                    $inPPr = true;
                } elseif ($reader->name === 'w:pStyle' && $inPPr) {
                    $currentParagraphStyle = $reader->getAttribute('w:val');
                } elseif ($reader->name === 'w:t') {
                    $inText = true;
                }
            } elseif ($reader->nodeType == XMLReader::TEXT || $reader->nodeType == XMLReader::SIGNIFICANT_WHITESPACE) {
                if ($inText && $inParagraph) {
                    $currentParagraphText .= $reader->value;
                }
            } elseif ($reader->nodeType == XMLReader::END_ELEMENT) {
                if ($reader->name === 'w:p') {
                    $inParagraph = false;
                    $text = trim($currentParagraphText);
                    
                    if ($text !== '') {
                        $isHeading = $this->isHeadingStyle($currentParagraphStyle, $text);
                        
                        if ($isHeading) {
                            // Save previous section if it had content
                            if (!empty($currentContent) || $currentHeading !== 'General Information') {
                                $sections[] = [
                                    'title' => $currentHeading,
                                    'content' => $this->formatContentHtml($currentContent),
                                ];
                            }
                            // Start new section
                            $currentHeading = $text;
                            $currentContent = [];
                        } else {
                            $currentContent[] = $text;
                        }
                    }
                } elseif ($reader->name === 'w:pPr') {
                    $inPPr = false;
                } elseif ($reader->name === 'w:t') {
                    $inText = false;
                }
            }
        }

        // Add the last remaining section
        if (!empty($currentContent) || $currentHeading !== 'General Information') {
            $sections[] = [
                'title' => $currentHeading,
                'content' => $this->formatContentHtml($currentContent),
            ];
        }

        $reader->close();
        unlink($tempFile);

        return $sections;
    }

    /**
     * Check if a style or text indicates a heading.
     */
    protected function isHeadingStyle(?string $style, string $text): bool
    {
        if (!$style) {
            // Fallback: If style is empty, but text is all uppercase, reasonably short, and not numeric, treat as heading
            $clean = preg_replace('/[^a-zA-Z\s]/', '', $text);
            if (strlen($clean) > 3 && strlen($clean) < 80 && strtoupper($text) === $text) {
                return true;
            }
            return false;
        }

        $styleLower = strtolower($style);
        
        // Match explicit Word heading styles
        if (strpos($styleLower, 'heading') !== false || 
            in_array($style, ['1', '2', '3', '4', 'Heading1', 'Heading2', 'Heading3', 'Heading4', 'TOCHeading'])
        ) {
            return true;
        }

        return false;
    }

    /**
     * Format array of paragraph strings into a clean HTML block.
     */
    protected function formatContentHtml(array $paragraphs): string
    {
        if (empty($paragraphs)) {
            return '<p class="text-gray-500 italic">No details provided for this section.</p>';
        }

        $html = '';
        foreach ($paragraphs as $p) {
            $html .= '<p class="mb-4 text-gray-700 dark:text-gray-300 leading-relaxed">' . e($p) . '</p>';
        }

        return $html;
    }
}
