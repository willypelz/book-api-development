<?php

/************************************
 ** File: Book Repository file  ******
 ** Date: 10th June 2020  ************
 ** Book Repository file  ************
 ** Author: Asefon pelumi M. *********
 ** Senior Software Developer ********
 * Email: pelumiasefon@gmail.com  ***
 * **********************************/

namespace App\Http\Repository;


use App\Library\Traits\IceAndFireTrait;
use App\Models\Book;
use function GuzzleHttp\Promise\all;

class BookRepository
{
    use IceAndFireTrait;

    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }


    /**
     * functions to get all books
     *
     * @return Book[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllBooks()
    {
        return $this->book->all();
    }

    // get single book in the application
//    public function getSingleBook($table_field, $query)
//    {
//        return $this->book->where($table_field, $query)->get();
//    }


    /**
     * function to update user details
     *
     * @param $request
     * @return Book
     */
    public function updateUser($request)
    {
        $this->book->name = $request->name;
        $this->book->isbn = $request->isbn;
        $this->book->authors = @$request->authors;
        $this->book->country = @$request->country;
        $this->book->number_of_pages = @$request->number_of_pages;
        $this->book->publisher = @$request->publisher;
        $this->book->release_date = @$request->release_date;
        $this->book->save();
        return $this->book;
    }


    /**
     * function to get books from external api
     *
     * @return array|string
     */
    public function getAllBooksFromExternal()
    {
        return $this->getIceAndFireBooks();
    }


    /**
     * function to get find a book by its name
     *
     * @param $name
     * @return array
     */
    public function findBookByName($name)
    {
        $bookCollection = array();

        $books = $this->getAllBooksFromExternal();

        if (is_string($books)) return $books;
        for ($bk = 0; $bk < count($books); $bk++) {
            if ($books[$bk]['name'] == $name) {
                array_push($bookCollection, $books[$bk]);
            }
        }

        return $bookCollection;

//       alternative is to use laravel filter collect helper function or recursion method
        /**
         * $filteredBook = collect($books)->filter(
         * function ($book) use ($query) {
         * return ($book['name'] == $query) ??   $book ;
         * });
         */
    }

}
