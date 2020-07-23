<?php

namespace App\Traits;

use DB;
use File;
use ImgUploader;
use App\Recruiter;

trait RecruiterTrait 
{
    private function deleteRecruiterLogo($id)
    {
        try {
            $recruiter = Recruiter::findOrFail($id);
            $image = $recruiter->image;
            if (!empty($image)) {
                File::delete(ImgUploader::real_public_path() . 'recruiters_images/' . $image);
            }
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }
    private function deleteCompanyLogo($id)
    {
        try {
            $company = Company::findOrFail($id);
            $image = $company->logo;
            if (!empty($image)) {
                File::delete(ImgUploader::real_public_path() . 'company_logos/thumb/' . $image);
                File::delete(ImgUploader::real_public_path() . 'company_logos/mid/' . $image);
                File::delete(ImgUploader::real_public_path() . 'company_logos/' . $image);
            }
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }
    
}