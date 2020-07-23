<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Bitly\BitlyClient;

class ShareUrlController extends Controller
{
    protected $token;
    protected $bitlyClient;

    public function __construct()
    {
        $this->token = '618cca418b4ae0940e76190e6a8ddb0499035b22';
        $this->bitlyClient = new BitlyClient($this->token);
    }

    public function getShortenUrl(Request $request) {
        
        $url = $request->get('longUrl');        
        $options = [
            'longUrl' => $url,
            'format' => 'json'
        ];
        $response = $this->bitlyClient->shorten($options);
        $shortenUrl = collect($response)->toArray();
        $data = collect(array_get($shortenUrl, 'data'))->toArray();
        $dataurl = array_get($data, 'url');
        $content = '<a target="_blank" href="https://www.facebook.com/share.php?u='.$dataurl.'" class="btn-floating btn-lg btn-facebook" type="button" role="button""><i class="fa fa-facebook"></i></a>'.
        '<a target="_blank" href="https://twitter.com/share?url='.$dataurl.'" class="btn-floating btn-lg btn-twitter" type="button" role="button""><i class="fa fa-twitter"></i></a>'.
        '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url='.$dataurl.'" class="btn-floating btn-lg btn-linkedin" type="button" role="button""><i class="fa fa-linkedin"></i></a>';
        echo $content;
        // return $shortenUrl;
    }
}
