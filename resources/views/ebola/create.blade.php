@extends('layouts.app')

@section('content')
<div class="bg-[#02050f] text-slate-100 min-h-screen relative overflow-hidden">
    <!-- Grid Overlay -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#0f172a_1px,transparent_1px),linear-gradient(to_bottom,#0f172a_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-25"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 z-10">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('ebola.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-200 uppercase tracking-widest font-mono mb-2">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Ebola Console
            </a>
            <h2 class="text-2xl font-black text-white tracking-tight">Log Individual Patient Case File</h2>
            <p class="text-xs text-slate-400 mt-1">Open a new biohazard surveillance registry sheet for Ebola monitoring.</p>
        </div>

        <!-- Registration Form Card -->
        <div class="bg-slate-950/70 border border-slate-900 rounded-2xl shadow-2xl overflow-hidden p-6 md:p-8">
            
            <form action="{{ route('ebola.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Basic Patient Information Section -->
                <div>
                    <h3 class="text-xs font-black tracking-widest text-red-400 uppercase font-mono mb-4 border-b border-slate-900 pb-2 flex items-center">
                        <span class="h-1.5 w-1.5 bg-red-500 rounded-full mr-2 block animate-pulse"></span>
                        1. Patient Particulars
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Patient Name -->
                        <div class="md:col-span-2">
                            <label for="patient_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">Patient Full Name</label>
                            <input type="text" name="patient_name" id="patient_name" required value="{{ old('patient_name') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 font-sans">
                            @error('patient_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Reporting -->
                        <div>
                            <label for="date_of_reporting" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">Reporting Date</label>
                            <input type="date" name="date_of_reporting" id="date_of_reporting" required value="{{ old('date_of_reporting', date('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 font-mono">
                            @error('date_of_reporting')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Patient Age -->
                        <div>
                            <label for="age" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">Age</label>
                            <input type="number" name="age" id="age" min="0" max="120" required value="{{ old('age') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 font-mono">
                            @error('age')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Patient Gender -->
                        <div>
                            <label for="gender" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">Gender</label>
                            <select name="gender" id="gender" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 cursor-pointer">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Institution / MCH Link -->
                        <div>
                            <label for="institution_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">MCH Facility</label>
                            <select name="health_institution_id" id="health_institution_id" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 cursor-pointer">
                                <option value="">Select Facility</option>
                                @foreach($mchs as $mch)
                                    <option value="{{ $mch->id }}" {{ old('health_institution_id') == $mch->id ? 'selected' : '' }}>{{ $mch->name }}</option>
                                @endforeach
                            </select>
                            @error('health_institution_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Outbreak Status and Quarantine Placement Section -->
                <div>
                    <h3 class="text-xs font-black tracking-widest text-indigo-400 uppercase font-mono mb-4 border-b border-slate-900 pb-2 flex items-center">
                        <span class="h-1.5 w-1.5 bg-indigo-500 rounded-full mr-2 block animate-pulse"></span>
                        2. Clinical Status & Quarantine
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Case Classification Status -->
                        <div>
                            <label for="status" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">Case Status</label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 cursor-pointer">
                                <option value="">Select Status</option>
                                <option value="Suspect" {{ old('status') === 'Suspect' ? 'selected' : '' }}>Suspect</option>
                                <option value="Probable" {{ old('status') === 'Probable' ? 'selected' : '' }}>Probable</option>
                                <option value="Confirmed" {{ old('status') === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quarantine / Admission Tier -->
                        <div>
                            <label for="quarantine_type" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">Quarantine Placement</label>
                            <select name="quarantine_type" id="quarantine_type" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 cursor-pointer">
                                <option value="">Select Placement</option>
                                <option value="Home Quarantine" {{ old('quarantine_type') === 'Home Quarantine' ? 'selected' : '' }}>Home Quarantine</option>
                                <option value="Institutional Quarantine" {{ old('quarantine_type') === 'Institutional Quarantine' ? 'selected' : '' }}>Institutional Quarantine</option>
                                <option value="Isolation (No O2)" {{ old('quarantine_type') === 'Isolation (No O2)' ? 'selected' : '' }}>Isolation (No O2)</option>
                                <option value="Isolation (With O2)" {{ old('quarantine_type') === 'Isolation (With O2)' ? 'selected' : '' }}>Isolation (With O2)</option>
                                <option value="ICU (No O2)" {{ old('quarantine_type') === 'ICU (No O2)' ? 'selected' : '' }}>ICU (No O2)</option>
                                <option value="ICU (With O2)" {{ old('quarantine_type') === 'ICU (With O2)' ? 'selected' : '' }}>ICU (With O2)</option>
                                <option value="ICU (Ventilator)" {{ old('quarantine_type') === 'ICU (Ventilator)' ? 'selected' : '' }}>ICU (Ventilator)</option>
                            </select>
                            @error('quarantine_type')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Laboratory Details and Patient Outcome Section -->
                <div>
                    <h3 class="text-xs font-black tracking-widest text-emerald-400 uppercase font-mono mb-4 border-b border-slate-900 pb-2 flex items-center">
                        <span class="h-1.5 w-1.5 bg-emerald-500 rounded-full mr-2 block animate-pulse"></span>
                        3. Laboratory Diagnostic & Prognosis
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Lab Test Status -->
                        <div>
                            <label for="test_status" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">Test Status</label>
                            <select name="test_status" id="test_status" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 cursor-pointer">
                                <option value="">Select Lab Result</option>
                                <option value="Not Tested" {{ old('test_status') === 'Not Tested' ? 'selected' : '' }}>Not Tested</option>
                                <option value="Sent for Test" {{ old('test_status') === 'Sent for Test' ? 'selected' : '' }}>Sent for Test</option>
                                <option value="Positive" {{ old('test_status') === 'Positive' ? 'selected' : '' }}>Positive</option>
                                <option value="Negative" {{ old('test_status') === 'Negative' ? 'selected' : '' }}>Negative</option>
                            </select>
                            @error('test_status')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Patient Outcome -->
                        <div>
                            <label for="outcome" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono mb-2">Prognosis Outcome</label>
                            <select name="outcome" id="outcome" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/60 text-sm font-bold text-white focus:outline-none focus:border-red-500 cursor-pointer">
                                <option value="">Select Outcome</option>
                                <option value="Active" {{ old('outcome') === 'Active' ? 'selected' : '' }}>Active Outpatient</option>
                                <option value="Recovered" {{ old('outcome') === 'Recovered' ? 'selected' : '' }}>Recovered & Discharged</option>
                                <option value="Deceased" {{ old('outcome') === 'Deceased' ? 'selected' : '' }}>Deceased</option>
                            </select>
                            @error('outcome')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="flex justify-end space-x-3 border-t border-slate-900 pt-6">
                    <a href="{{ route('ebola.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-slate-800 rounded-xl text-sm font-medium text-slate-400 bg-slate-950 hover:bg-slate-900 hover:text-white transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-xl text-sm font-semibold text-white bg-red-650 hover:bg-red-700 shadow-lg shadow-red-950/20 transition cursor-pointer">
                        Register Patient Case
                    </button>
                </div>
            </form>
            
        </div>

    </div>
</div>
@endsection
