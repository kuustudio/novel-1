<?php


namespace app\index\controller;


use app\model\Book;
use app\model\Cate;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

class Booklist extends Base
{
    public function index() {
        return view($this->tpl);
    }

    public function getBooks()
    {
        $map = array();
        $cate = input('cate_id');
        if (is_null($cate) || $cate == '-1') {

        } else {
            $map[] = ['cate_id', '=', $cate];
        }
        $words = input('words');
        if (is_null($words) || $words == '-1') {

        } else {
            $map[] = ['words', '=', $words];
        }
        $end = input('end');
        if (is_null($end) || $end == -1) {

        } else {
            $map[] = ['end', '=', $end];
        }
        $page = input('page');
        try {
            $books = Book::where($map)->with('chapters')->order('update_time', 'desc')
                ->limit($page, 21)->selectOrFail();
            foreach ($books as &$book) {
                $book['chapter_count'] = count($book->chapters);
                if ($this->end_point == 'id') {
                    $book['param'] = $book['id'];
                } else {
                    $book['param'] = $book['unique_id'];
                }
            }
            return json(['err' => 0, 'books' => $books]);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
            return json(['err' => 1]);
        }
    }

    public function getOptions() {
        $cates = cache('cates');
        if (!$cates ) {
            $cates = Cate::select();
            cache('cates', $cates, null, 'redis');
        }
        return json([
            'cates' => $cates
        ]);
    }
}