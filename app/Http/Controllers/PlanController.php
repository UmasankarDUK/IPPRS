<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Block;
use App\Models\Localbody;
use App\Models\HealthInstitution;
use App\Models\PlanSection;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a directory of all plans.
     */
    public function index()
    {
        $districts = District::where('state_id', 12)->withCount('blocks')->get();
        $blocks = Block::with('district')->withCount('localbodies')->get();
        $localbodies = Localbody::with('block')->withCount('healthInstitutions')->get();
        $institutions = HealthInstitution::with('localbody')->get();

        return view('plans.index', compact('districts', 'blocks', 'localbodies', 'institutions'));
    }

    /**
     * Resolve the planable entity by type and ID.
     */
    protected function getPlanable(string $type, $id)
    {
        switch (strtolower($type)) {
            case 'district':
                return District::findOrFail($id);
            case 'block':
                return Block::findOrFail($id);
            case 'localbody':
                return Localbody::findOrFail($id);
            case 'institution':
                return HealthInstitution::findOrFail($id);
            default:
                abort(404, 'Invalid plan category');
        }
    }

    /**
     * Show the detailed plan in GitBook style.
     */
    public function show(string $type, $id, $sectionId = null)
    {
        $entity = $this->getPlanable($type, $id);
        
        // Fetch all sections for the sidebar
        $sections = $entity->planSections()->orderBy('section_order')->get();
        
        // Select active section
        $activeSection = null;
        if ($sectionId) {
            $activeSection = PlanSection::findOrFail($sectionId);
        } else {
            $activeSection = $sections->first();
        }

        return view('plans.show', compact('entity', 'type', 'sections', 'activeSection'));
    }

    /**
     * Form to create a new section.
     */
    public function createSection(string $type, $id)
    {
        $entity = $this->getPlanable($type, $id);
        return view('plans.create_section', compact('entity', 'type'));
    }

    /**
     * Store a newly created section.
     */
    public function storeSection(Request $request, string $type, $id)
    {
        $entity = $this->getPlanable($type, $id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $maxOrder = $entity->planSections()->max('section_order') ?? 0;

        $section = $entity->planSections()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'section_order' => $maxOrder + 1,
        ]);

        return redirect()->route('plans.show', ['type' => $type, 'id' => $id, 'sectionId' => $section->id])
                         ->with('success', 'Plan chapter added successfully!');
    }

    /**
     * Form to edit an existing section.
     */
    public function editSection($sectionId)
    {
        $section = PlanSection::findOrFail($sectionId);
        
        // Determine type and entity
        $entity = $section->planable;
        $type = strtolower(class_basename($entity));
        if ($type === 'healthinstitution') {
            $type = 'institution';
        }

        return view('plans.edit_section', compact('section', 'entity', 'type'));
    }

    /**
     * Update an existing section.
     */
    public function updateSection(Request $request, $sectionId)
    {
        $section = PlanSection::findOrFail($sectionId);
        $entity = $section->planable;
        $type = strtolower(class_basename($entity));
        if ($type === 'healthinstitution') {
            $type = 'institution';
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $section->update($validated);

        return redirect()->route('plans.show', ['type' => $type, 'id' => $entity->id, 'sectionId' => $section->id])
                         ->with('success', 'Plan chapter updated successfully!');
    }

    /**
     * Delete an existing section.
     */
    public function destroySection($sectionId)
    {
        $section = PlanSection::findOrFail($sectionId);
        $entity = $section->planable;
        $type = strtolower(class_basename($entity));
        if ($type === 'healthinstitution') {
            $type = 'institution';
        }
        
        $section->delete();

        // Reorder remaining sections
        $sections = $entity->planSections()->orderBy('section_order')->get();
        foreach ($sections as $index => $s) {
            $s->update(['section_order' => $index]);
        }

        return redirect()->route('plans.show', ['type' => $type, 'id' => $entity->id])
                         ->with('success', 'Plan chapter deleted successfully!');
    }
}
