<?php

namespace App\Http\Controllers;

use Auth;
use App;
use App\Seo;
use App\Job;
use App\Company;
use App\FunctionalArea;
use App\Country;
use App\Video;
use App\Testimonial;
use App\Slider;
use Illuminate\Http\Request;
use Redirect;
use App\Traits\CompanyTrait;
use App\Traits\FunctionalAreaTrait;
use App\Traits\CityTrait;
use App\Traits\JobTrait;
use App\Traits\Active;
use App\Helpers\DataArrayHelper;
use App\Helpers\ProfileArrayHelper;


class IndexController extends Controller
{

    use CompanyTrait;
    use FunctionalAreaTrait;
    use CityTrait;
    use JobTrait;
    use Active;

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
    /**
     * Redireccion hacia la vista de inicio o de llenado de informacion obligatoria para completar el perfil
     */
    public function index()
    {        
        $user=Auth::user();
        if ($user!=null) {
            $perfil_completo=ProfileArrayHelper::comprueba();
            if ($perfil_completo['cumplePerfil'] & $perfil_completo['cumpleVideo'] ) {
                $topCompanyIds = $this->getCompanyIdsAndNumJobs(16);
                $topFunctionalAreaIds = $this->getFunctionalAreaIdsAndNumJobs(32);
                $topIndustryIds = $this->getIndustryIdsFromCompanies(32);
                $topCityIds = $this->getCityIdsAndNumJobs(32);
                $featuredJobs = Job::active()->featured()->notExpire()->limit(12)->get();
                $latestJobs = Job::active()->notExpire()->orderBy('id', 'desc')->limit(12)->get();
                $video = Video::getVideo();
                $testimonials = Testimonial::langTestimonials();

                $functionalAreas = DataArrayHelper::langFunctionalAreasArray();
                $countries = DataArrayHelper::langCountriesArray();
                $country_id = IpClient::getIpClient();
                $sliders = Slider::langSliders();

                $seo = SEO::where('seo.page_title', 'like', 'front_index_page')->first();
                return view('welcome')
                                ->with('topCompanyIds', $topCompanyIds)
                                ->with('topFunctionalAreaIds', $topFunctionalAreaIds)
                                ->with('topCityIds', $topCityIds)
                                ->with('topIndustryIds', $topIndustryIds)
                                ->with('featuredJobs', $featuredJobs)
                                ->with('latestJobs', $latestJobs)
                                ->with('functionalAreas', $functionalAreas)
                                ->with('countries', $countries)
                                ->with('country_id', $country_id)
                                ->with('sliders', $sliders)
                                ->with('video', $video)
                                ->with('testimonials', $testimonials)
                                ->with('seo', $seo);  
            }else {
                return \Redirect::route('home.profile');
            }
        } else {
            $topCompanyIds = $this->getCompanyIdsAndNumJobs(16);
            $topFunctionalAreaIds = $this->getFunctionalAreaIdsAndNumJobs(32);
            $topIndustryIds = $this->getIndustryIdsFromCompanies(32);
            $topCityIds = $this->getCityIdsAndNumJobs(32);
            $featuredJobs = Job::active()->featured()->notExpire()->limit(12)->get();
            $latestJobs = Job::active()->notExpire()->orderBy('id', 'desc')->limit(12)->get();
            $video = Video::getVideo();
            $testimonials = Testimonial::langTestimonials();

            $functionalAreas = DataArrayHelper::langFunctionalAreasArray();
            $countries = DataArrayHelper::langCountriesArray();
            $country_id = IpClient::getIpClient();
            $sliders = Slider::langSliders();

            $seo = SEO::where('seo.page_title', 'like', 'front_index_page')->first();
            return view('welcome')
                            ->with('topCompanyIds', $topCompanyIds)
                            ->with('topFunctionalAreaIds', $topFunctionalAreaIds)
                            ->with('topCityIds', $topCityIds)
                            ->with('topIndustryIds', $topIndustryIds)
                            ->with('featuredJobs', $featuredJobs)
                            ->with('latestJobs', $latestJobs)
                            ->with('functionalAreas', $functionalAreas)
                            ->with('countries', $countries)
                            ->with('country_id', $country_id)
                            ->with('sliders', $sliders)
                            ->with('video', $video)
                            ->with('testimonials', $testimonials)
                            ->with('seo', $seo);
        }        
    }
    /**
     * Redireccion hacia la vista de inicio o de llenado de informacion obligatoria para completar el perfil
     */
    public function inicio()
    {
        $topCompanyIds = $this->getCompanyIdsAndNumJobs(16);
        $topFunctionalAreaIds = $this->getFunctionalAreaIdsAndNumJobs(32);
        $topIndustryIds = $this->getIndustryIdsFromCompanies(32);
        $topCityIds = $this->getCityIdsAndNumJobs(32);
        $featuredJobs = Job::active()->featured()->notExpire()->limit(12)->get();
        $latestJobs = Job::active()->notExpire()->orderBy('id', 'desc')->limit(12)->get();
        $video = Video::getVideo();
        $testimonials = Testimonial::langTestimonials();

        $functionalAreas = DataArrayHelper::langFunctionalAreasArray();
        $countries = DataArrayHelper::langCountriesArray();
        $country_id = IpClient::getIpClient();
        $sliders = Slider::langSliders();

        $seo = SEO::where('seo.page_title', 'like', 'front_index_page')->first();
        return view('welcome')
                        ->with('topCompanyIds', $topCompanyIds)
                        ->with('topFunctionalAreaIds', $topFunctionalAreaIds)
                        ->with('topCityIds', $topCityIds)
                        ->with('topIndustryIds', $topIndustryIds)
                        ->with('featuredJobs', $featuredJobs)
                        ->with('latestJobs', $latestJobs)
                        ->with('functionalAreas', $functionalAreas)
                        ->with('countries', $countries)
                        ->with('country_id', $country_id)
                        ->with('sliders', $sliders)
                        ->with('video', $video)
                        ->with('testimonials', $testimonials)
                        ->with('seo', $seo);
    }
    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');
        $return_url = $request->input('return_url');
        $is_rtl = $request->input('is_rtl');
        $localeDir = ((bool) $is_rtl) ? 'rtl' : 'ltr';

        session(['locale' => $locale]);
        session(['localeDir' => $localeDir]);

        return Redirect::to($return_url);
    }

}
