<?php

namespace App\Services\Complaint\ComplaintConfigs;

use App\Models\Complaint\CompetentAuthority;
use App\Models\Complaint\ComplaintType;
use App\Models\Complaint\Destination;

class ComplaintConfigService
{
    /**
     * Retrieves all configuration lists required for creating a new complaint.
     *
     * This method fetches data from the database to populate dropdowns or other
     * form fields on the complaint creation page. It ensures data is returned
     * in a structured, self-describing format for the frontend.
     *
     * @return array A JSON response containing key-value pairs of the configuration lists,
     * with a 200 OK HTTP status code.
     */
    public function getCreationConfigData()
    {
        // Fetch only the 'id' and 'name' columns for efficiency.
        $complaintTypes = ComplaintType::all(['id', 'name']);
        $destinations = Destination::all(['id', 'name']);
        $competentAuthorities = CompetentAuthority::all(['id', 'name']);

        // Return the data in a self-describing JSON object with meaningful keys.
        // This is more robust than an unkeyed array, as the client can access
        // data by name (e.g., 'complaint_types') instead of index (e.g., index 0).
        return [
            'complaint_types' => $complaintTypes,
            'destinations' => $destinations,
            'competent_authorities' => $competentAuthorities,
        ];
    }
}
