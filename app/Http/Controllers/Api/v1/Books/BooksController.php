<?php

namespace App\Http\Controllers\Api\v1\Books;

use App\Http\Controllers\Controller;
use App\Http\Library\RestFullResponse\ApiResponse;
use App\Http\Repository\BookRepository;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\v1\Books\BookResource;
use App\Http\Resources\v1\Books\BookResourceCollection;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    protected $bookRepository;
    protected $apiResponse;


    /**
     * BooksController constructor.
     * @param BookRepository $bookRepository
     * @param ApiResponse $apiResponse
     */
    public function __construct(BookRepository $bookRepository, ApiResponse $apiResponse)
    {
        $this->bookRepository = $bookRepository;
        $this->apiResponse = $apiResponse;
    }

    /**
     * @group Book management
     *
     *  Book Collection
     *
     * An Endpoint to get all Book in the system
     *
     * @param Book $books
     * @return \Illuminate\Http\JsonResponse
     * @apiResourceCollection \App\Http\Resources\v1\Book\BookResourceCollection
     * @apiResourceModel \App\Models\Book
     */
    public function index()
    {
        return $this->apiResponse->respondWithNoPagination(
            new BookResourceCollection($this->bookRepository->getAllBooks())
        );
    }


    /**
     * @group Book management
     *
     *  Books Collection
     *
     * An Endpoint to get all Books in the system
     *
     * @param CreateBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     * @apiResourceCollection \App\Http\Resources\v1\Book\BookResourceCollection
     * @apiResourceModel \App\Models\Book
     */
    public function store(CreateBookRequest $request, Book $book)
    {
        $book = $book->create($request->toArray());
        return $this->apiResponse->respondWithNoPagination(new BookResource($book),
            'Book created successfully');
    }


    /**
     * @group Book management
     *
     *  Books Collection
     *
     * An Endpoint to get all Books in the system
     *
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     * @apiResourceCollection \App\Http\Resources\v1\Book\BookResourceCollection
     * @apiResourceModel \App\Models\Book
     */
    public function show(Book $book)
    {
        return $this->apiResponse->respondWithNoPagination($book, 'Book fetch successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UpdateBookRequest $request
     * @return void
     */
    public function update(UpdateBookRequest $request)
    {
        $updatedBook = $this->bookRepository->updateUser($request);
        return $this->apiResponse->respondWithNoPagination($updatedBook,
            "The book $updatedBook->name was updated successfully");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Book $book
     * @return void
     * @throws \Exception
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return $this->apiResponse->respondDeleted("The book $book->name was deleted successfully");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return void
     */
    public function externalBook(Request $request)
    {
        $query = "A Clash of Kings";
        $books = $this->bookRepository->getAllBooksFromExternal();

        $bookCollection = array();

        for ($bk = 0; $bk < count($books); $bk++) {
            if ($books[$bk]['name'] == $query) {
                array_push($bookCollection, $books[$bk]);
            }
        }
//        $filteredBook = collect($books)->filter(
//            function ($book) use ($query) {
//                return ($book['name'] == $query) ??  (object) $book ;
//            });
//        return  $filteredBook;
        return $this->apiResponse->respondWithNoPagination(new BookResourceCollection($filteredBook));
    }






//
//    /**
//     * @group Account management
//     * User and Application Registration
//     * This creates a user in the application
//     *
//     * @bodyParam first_name string required The first name of the user.
//     * @bodyParam last_name string required The first name of the user.
//     * @bodyParam email email required The email of the user.
//     * @bodyParam role_id int required The role the user belongs to.
//     * @bodyParam application_role_id int required The role in the application the user will be added to
//     * @bodyParam username  the username of the user.
//     * @authenticated
//     * @response {
//     *  "status": true,
//     *  "message": "message",
//     *  "data" : [],
//     *  "status_code" : 200
//     * }
//     * @param StoreUser $request
//     * @param User $user
//     * @param UserApplicationRepository $applicationRepository
//     * @return \Illuminate\Http\Response
//     * @throws \Exception
//     */
//
//    public function store(StoreUser $request, User $user, UserApplicationRepository $applicationRepository)
//    {
//        //check if email already exists
//        $userDetails = $user->whereEmail($request->email)->first();
//        if (empty($userDetails)) {
//            $userDetails = $user->create($request->toArray());
//            // creates users and sends a mail
//            event(new Registered($userDetails));
//        }
//        $app_name = config('ums.app_name'); // gets this application name
//        $application = $request->application->name; //gets application making the request
//        if (strtolower($app_name) != strtolower($application) || !empty($request->application_id)) {
//            $request['application_id'] = $request->application_id;
//            $userApplication = $applicationRepository->createUserApplication($request, $userDetails);
//            return apiResponse(true,
//                trans('User created and added to application'),
//                [], JsonResponse::HTTP_OK);
//        }
//        return apiResponse(true, 'User created successfully', []);
//    }
//
//    /**
//     * @group Account management
//     * User Registration
//     * This creates a user
//     *
//     *
//     * @bodyParam first_name string required The first name of the user.
//     * @bodyParam last_name string required The first name of the user.
//     * @bodyParam email email required The email of the user.
//     * @bodyParam role_id integer required The role the user belongs to.
//     * @bodyParam username string required  the username of the user.
//     * @bodyParam password string required  the password of the user.
//     * @param SignUpUser $request
//     * @param User $user
//     * @return \Illuminate\Http\Response
//     * @throws \Exception
//     */
//
//    public function signUp(signUpUser $request, User $user)
//    {
//        // creates users
//        $user = $user->create($request->toArray());
//
//        event(new Registered($user));
//        return apiResponse(true, 'User created successfully', []);
//    }
//
//    /**
//     * @group Account management
//     * User Information
//     * Endpoint to get user information
//     *
//     * @urlParam user  required  The ID of the user.
//     * @param User $user
//     * @return \Illuminate\Http\Response
//     * @throws \Exception
//     * @authenticated
//     * @apiResourceCollection \App\Http\Resources\v1\User\UserResource
//     * @apiResourceModel \App\Models\User
//     */
//    public function show(User $user)
//    {
//        return apiResponse(false, trans('User fetched succesfully'),
//            new UserResource($user), JsonResponse::HTTP_OK);
//    }
//
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param UpdateUser $request
//     * @param User $user
//     * @return \Illuminate\Http\Response
//     * @throws \Exception
//     */
//    public function update(UpdateUser $request, User $user)
//    {
//        //TODO: try to put a check on who is updating this user
////        $user->updateUser($request);
////        return apiResponse(true, trans('Updated successfully'), [],
////            JsonResponse::HTTP_OK);
//    }
//
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param Book $book
//     * @return void
//     */
//    public function destroy(Book $book)
//    {
//        //
////        $user->delete();
////        return apiResponse(true, trans('deleted succesfully'), [], JsonResponse::HTTP_OK);
//    }


}
