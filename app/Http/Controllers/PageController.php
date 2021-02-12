<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Link;
use App\Models\View;
use App\Models\Click;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(string $slug)
    {
        $validator = Validator::make(['slug' => $slug], [
            'slug' => 'exists:pages'
        ]);

        if ($validator->fails()) {
            return view('notfound');
        } else {
            $page = Page::where('slug', $slug)->first();

            $pageId = $page->public_id;
            $fontColor = $page->op_font_color;
            $profileImage = url('/media/uploads/' . $page->op_profile_image);
            $title = $page->op_title;
            $description = $page->op_description;
            $fbPixel = $page->op_fb_pixel;
            $bgType = $page->op_bg_type;
            $bgValue = $page->op_bg_value;

            $principalColor = '#ffffff';
            $secondColor = '#ffffff';

            switch ($bgType) {
                case 'image':
                    $bgValue = "url('" . url('/media/uploads/' . $bgValue) . "')";
                    break;
                case 'gradient':
                    $colors = explode(',', $bgValue);

                    $color1 = $colors[0];
                    $color2 = $colors[1];

                    $bgValue = "linear-gradient(90deg, $color1, $color2)";
                    $principalColor = $color1;
                    $secondColor = $color2;
                    break;
            }

            $links = Link::where('id_page', $pageId)->where('status', 1)
                ->orderBy('order')->get();

            $view = View::where('id_page', $pageId)
                ->where('view_date', gmdate('Y-m-d'))->first();

            if ($view) {
                $view->total++;
                $view->save();
            } else {
                $view = new View();

                do {
                    $viewPublicId = Str::uuid()->toString();
                } while (View::where('public_id', $viewPublicId)->count() > 0);

                $view->public_id = $viewPublicId;
                $view->id_page = $pageId;
                $view->view_date = gmdate('Y-m-d');
                $view->total++;
                $view->save();
            }


            return view('page', [
                'font_color' => $fontColor,
                'profile_image' => $profileImage,
                'title' => $title,
                'description' => $description,
                'fb_pixel' => $fbPixel,
                'principal_color' => $principalColor,
                'second_color' => $secondColor,
                'bg' => $bgValue,
                'links' => $links
            ]);
        };
    }

    public function linkAction (string $linkId)
    {
        $validator = Validator::make(['public_id' => $linkId], [
            'public_id' => 'exists:links'
        ]);

        if ($validator->fails()) {
            return view('notfound');
        } else {
            $link = Link::where('public_id', $linkId)->first();

            $click = Click::where('id_link', $linkId)
                ->where('click_date', gmdate('Y-m-d'))->first();

            if ($click) {
                $click->total++;
                $click->save();
            } else {
                $click = new Click();

                do {
                    $clickPublicId = Str::uuid()->toString();
                } while (Click::where('public_id', $clickPublicId)->count() > 0);

                $click->public_id = $clickPublicId;
                $click->id_link = $linkId;
                $click->click_date = gmdate('Y-m-d');
                $click->total++;
                $click->save();
            }

            return redirect()->away($link->href);
        };
    }
}
