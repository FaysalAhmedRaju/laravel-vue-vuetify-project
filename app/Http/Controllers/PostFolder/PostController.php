<?php

namespace App\Http\Controllers\PostFolder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use Log;
use Response;

class PostController extends Controller
{
    public function __construct(Post $post)
    {
        $this->post = $post;
    }



}
