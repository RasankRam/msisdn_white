<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePageRequest;
use App\Http\Resources\PageCollection;
use App\Models\Page;
use function PHPUnit\Framework\isEmpty;

class ContentController extends Controller
{
    public function show($page) {
      $blocks= Page::where('page',$page)->get();
      if ($blocks->isEmpty()) {
        return $this->makeErrorResponse("$page blocks not found");
      }
      return $this->makeSuccessResponse(new PageCollection($blocks));
    }

    public function update(UpdatePageRequest $request) {
      $block = Page::where('page', $request->page)->where('block',$request->block)->first();
      if (!$block) {
        $this->makeErrorResponse('Block not found');
      }
      $block->content = $request->input('content');
      $block->save();
      return $this->makeSuccessResponse('Block has been updated successfully');
    }

}
