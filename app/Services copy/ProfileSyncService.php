<?php

namespace App\Services;

use App\Models\Resident;
use App\Models\Profile;

class ProfileSyncService
{
    public function syncFromResident(Resident $resident)
    {
        $user   = $resident->user;   // if resident linked to user
        $guest  = $resident->guest;  // if resident linked to guest

        // Extract all fields smartly (highest priority → lowest)
        $data = [
            'user_id'           => $resident->user_id,
            'resident_id'       => $resident->id,

            // Personal Details
            'name'              => $user->name ?? $guest->name ?? $resident->name ?? null,
            'gender'            => $guest->gender ?? $user->gender ?? null,
            'dob'               => $guest->dob ?? null,

            'mobile'            => $guest->number ?? $resident->number ?? null,
            'alternate_mobile'  => $guest->alternate_phone ?? null,

            'email'             => $resident->email ?? $user->email ?? null,

            // Address
            'address_line1'     => $guest->address_line1 ?? null,
            'address_line2'     => $guest->address_line2 ?? null,
            'city'              => $guest->city ?? null,
            'state'             => $guest->state ?? null,
            'country'           => $resident->country ?? 'India',   // 👍 Fallback added
            'pincode'           => $guest->pincode ?? null,

            // Family Information
            'father_name'       => $resident->fathers_name ?? null,
            'father_mobile'      => $resident->father_mobile ?? null,
            'mother_name'       => $resident->mothers_name ?? null,
            'mother_mobile'      => $guest->mother_mobile ?? null,
            'parent_mobile'      => $resident->parent_no ?? null,

            'guardian_name'     => $guest->local_guardian_name ?? null,
            'guardian_mobile'    => $resident->guardian_no ?? null,
            'guardian_relation' => $guest->guardian_relation ?? null,

            // Emergency
            'emergency_name'        => $guest->emergency_name ?? null,
            'emergency_relation'    => $guest->emergency_relation ?? null,
            'emergency_mobile'       => $guest->emergency_no ?? null,

            // ID Proofs
            'aadhaar_number'    => $guest->aadhaar_number ?? null,
            'aadhaar_document'  => $guest->aadhaar_document ?? null,
            'image'             => $guest->image ?? null,
            'signature'         => $guest->signature ?? null,

            // Academic
            'scholar_number'     => $resident->scholar_no ?? null,
            'course'            => $guest->course->name ?? null,
            'branch'            => $guest->branch ?? null,
            'semester'          => $guest->course->semester ?? null,
            'admission_year'    => $guest->admission_year ?? null,

            // Hostel Info
            'is_hosteler'       => $resident->is_hosteler ?? 1,
            'hostel_status'     => $resident->status ?? 'active',
            'check_in_date'      => $resident->check_in_date ?? null,
            'check_out_date'      => $resident->check_out_date ?? null,

            // Health
            'blood_group'       => $guest->blood_group ?? null,
            'medical_conditions'=> $guest->medical_conditions ?? null,

            // Notes
            'remarks'           => $resident->remarks ?? null,
        ];

        // Create or update profile
        return Profile::updateOrCreate(
            ['resident_id' => $resident->id],  // unique key
            $data
        );
    }
}
