<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Job;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         // $today = Carbon::now();
        // $totalActiveUsers = User::where('is_active', 1)->count();
        // $totalVerifiedUsers = User::where('verified', 1)->count();
        // $totalTodaysUsers = User::where('created_at', 'like', $today->toDateString() . '%')->count();
        // $recentUsers = User::orderBy('id', 'DESC')->take(25)->get();
        // $totalActiveJobs = Job::where('is_active', 1)->count();
        // $totalFeaturedJobs = Job::where('is_featured', 1)->count();
        // $totalTodaysJobs = Job::where('created_at', 'like', $today->toDateString() . '%')->count();
        // $recentJobs = Job::orderBy('id', 'DESC')->take(25)->get();

        // return view('admin.home')
        //             ->with('totalActiveUsers', $totalActiveUsers)
        //             ->with('totalVerifiedUsers', $totalVerifiedUsers)
        //             ->with('totalTodaysUsers', $totalTodaysUsers)
        //             ->with('recentUsers', $recentUsers)
        //             ->with('totalActiveJobs', $totalActiveJobs)
        //             ->with('totalFeaturedJobs', $totalFeaturedJobs)
        //             ->with('totalTodaysJobs', $totalTodaysJobs)
        //             ->with('recentJobs', $recentJobs);

        
        //Totals, GENERALS

        /*========================Users========================*/
        $todayUsersTotal=DB::select('SELECT count(1) total_data
                                    FROM forge.users
                                        WHERE  CAST(created_at AS DATE)=CAST(now() AS DATE)
                                    ;'
        );

        $activeUsersTotal=DB::select('SELECT count(1) total_data
                                        FROM forge.users
                                            WHERE CAST(created_at AS DATE)<CAST(now() AS DATE)
                                                AND is_active=1
                                                AND verified=0
                                    ;'
        );

        $verifiedUsersTotal=DB::select('SELECT count(1) total_data
                                        FROM forge.users
                                            WHERE CAST(created_at AS DATE)<CAST(now() AS DATE)
                                                AND is_active=1
                                                AND verified=1
                                    ;'
        );

        $videosRecordedTotal=DB::select('SELECT count(1) total_data
                                        FROM forge.video_apply
                                            WHERE is_active=1 
                                        ;'
        );

        $testLanguageTotal=DB::select('SELECT count(1) total_data
                                FROM forge.log_lang_test
                                    WHERE is_active=1
                                ;'
        );

        /*========================Companies========================*/
        $newCompaniesTotal=DB::select('SELECT count(1) total_data
                                        FROM forge.companies        
                                            WHERE  CAST(created_at AS DATE)<CAST(now() AS DATE)
                                                AND is_active=1
                                    ;'
        );

        $activeCompaniesTotal=DB::select('SELECT count(1) total_data
                                            FROM forge.companies
                                                WHERE CAST(created_at AS DATE)<CAST(now() AS DATE)
                                                    AND is_active=1
                                                    AND verified=0
                                        ;'
        );

        $verifiedCompaniesTotal=DB::select('SELECT count(1) total_data
                                            FROM forge.companies
                                                WHERE CAST(created_at AS DATE)<CAST(now() AS DATE)
                                                    AND is_active=1
                                                    AND verified=1
                                        ;'
        );

        $todayJobsTotal=DB::select('SELECT count(1) total_data
                                FROM jobs
                                WHERE CAST(created_at AS DATE)=CAST(now() AS DATE)
                                    and is_active=1
                            ;'
        );

        $activeJobsTotal=DB::select('SELECT count(1) total_data
                                        FROM jobs
                                        WHERE  CAST(created_at AS DATE)<CAST(now() AS DATE)
                                        and is_active=1
                                    ;'
        );
     
        $todayApplicantsTotal=DB::select('SELECT count(1) total_data
                                            FROM job_apply appli
                                            WHERE  CAST(created_at AS DATE)=CAST(now() AS DATE)
                                                and appli.state=1
                                        ;'
        );

        //Graphics
        //per_day = 7 days
        //six_month_range = 6 Months
        /*========================Users========================*/
        $newUsersPerDay=DB::select('CALL `forge` . `sp_created_users_per_day`(now())');
        $activeUsers=DB::select('CALL `forge` . `sp_active_users_six_month_range`(now())');
        $verifiedUsers=DB::select('CALL `forge` . `sp_verified_users_six_month_range`(now())');
        $testLanguage=DB::select('CALL `forge` . `test_language_six_month_range`(now())'); 

        /*========================Comapanies========================*/
        $newCompanyPerDay=DB::select('CALL `forge` . `sp_created_companies_per_day`(now())');
        $activeCompany=DB::select('CALL `forge` . `sp_active_company_six_month_range`(now())');
        $verifiedCompany=DB::select('CALL `forge` . `sp_verified_companies_six_month_range`(now())');
        $jobsApplicantPerDay=DB::select('CALL `forge` . `sp_jobs_apply_per_day`(now())');
        $activeJobs=DB::select('CALL `forge` . `sp_active_jobs_six_month_range`(now())');
        $topCompaniesMonths=DB::select('CALL `forge` . `sp_top_five_companies_six_month_range`(now())');
        $topCompaniesTotal=DB::select('CALL `forge` . `sp_top_five_companies_total`(now())');

        //Usuarios y Empresas Recientes
        $recentUsers = User::orderBy('id', 'DESC')->take(25)->get();
        $recentJobs = Job::orderBy('id', 'DESC')->take(25)->get();

        return view('admin.home')
                            //totals
                            ->with('todayUsersTotal', $todayUsersTotal[0]->total_data)
                            ->with('activeUsersTotal', $activeUsersTotal[0]->total_data)
                            ->with('verifiedUsersTotal', $verifiedUsersTotal[0]->total_data)
                            ->with('videosRecordedTotal', $videosRecordedTotal[0]->total_data)
                            ->with('testLanguageTotal', $testLanguageTotal[0]->total_data)
                            ->with('newCompaniesTotal', $newCompaniesTotal[0]->total_data)
                            ->with('activeCompaniesTotal', $activeCompaniesTotal[0]->total_data)
                            ->with('verifiedCompaniesTotal', $verifiedCompaniesTotal[0]->total_data)
                            ->with('todayJobsTotal', $todayJobsTotal[0]->total_data)
                            ->with('activeJobsTotal', $activeJobsTotal[0]->total_data)
                            ->with('todayApplicantsTotal', $todayApplicantsTotal[0]->total_data)
                            //Graphics
                            ->with('newUsersPerDay',$newUsersPerDay)
                            ->with('activeUsers',$activeUsers)
                            ->with('verifiedUsers',$verifiedUsers)
                            ->with('testLanguage',$testLanguage)
                            ->with('newCompanyPerDay',$newCompanyPerDay)
                            ->with('activeCompany',$activeCompany)
                            ->with('verifiedCompany',$verifiedCompany)
                            ->with('jobsApplicantPerDay',$jobsApplicantPerDay)
                            ->with('activeJobs',$activeJobs)
                            ->with('topCompaniesMonths',$topCompaniesMonths)
                            ->with('topCompaniesTotal', $topCompaniesTotal)                            
                            //
                            ->with('recentUsers', $recentUsers)
                            ->with('recentJobs', $recentJobs)
        ;    
    }

}
