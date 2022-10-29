<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    private function is_login()
    {
        if(Auth::user()) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public function show()
    {
        $reviews = DB::table('review_barang')->orderby('id', 'desc')->get();
        return view('show', ['reviews'=>$reviews]);
    }

    public function add()
    {
        if($this->is_login())
        {
            return view('add');
        }
 
        else
        {
           return redirect('/login');
        }
    }
 
    public function add_process(Request $review)
    {
        DB::table('review_barang')->insert([
            'nama_barang'=>$review->nama_barang,
            'review_barang'=>$review->review_barang
        ]);
        return redirect()->action('ReviewController@show');
    }

    public function detail($id)
    {
        $review = DB::table('review_barang')->where('id', $id)->first();
        return view('detail', ['review'=>$review]);
    }

    public function edit($id)
    {
        if($this->is_login())
        {
            $review = DB::table('review_barang')->where('id', $id)->first();
            return view('edit', ['review'=>$review]);
        }
 
        else
        {
           return redirect('/login');
        }
    }

    public function delete($id)
    {
        if($this->is_login())
        {
            DB::table('review_barang')->where('id', $id)
                                      ->delete();
            Session()->put('success', 'Data berhasil dihapus');
            return redirect()->action('ReviewController@show_by_admin');
        }
 
        else
        {
           return redirect('/login');
        }
    }

    public function show_by_admin()
    {
        if($this->is_login())
        {
            $reviews = DB::table('review_barang')->orderby('id', 'desc')->get();
            return view('adminshow', ['reviews'=>$reviews]);
        }
 
        else
        {
            return redirect('/login');
        }
    }

    public function edit_process(Request $review)
    {
        $id = $review->id;
        $nama_barang = $review->nama_barang;
        $review_barang = $review->review_barang;
        DB::table('review_barang')->where('id', $id)
                                  ->update(['nama_barang' => $nama_barang, 'review_barang' => $review_barang]);
        Session()->put('success', 'Data berhasil diedit');
        return redirect()->action('ReviewController@show_by_admin');
    }
}
