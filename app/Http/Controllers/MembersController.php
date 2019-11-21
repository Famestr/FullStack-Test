<?php
namespace 
App\Http\Controllers;
use Illuminate\Http\Request;
use App\members;
class MembersController extends Controller {

  public function getMembers(Request $request) {
    if ( $request->input('showdata') ) {
      return members::orderBy('joined_date', 'desc')->get();
    }

    $columns = ['id', 'firstname', 'surname', 'email', 'gender', 'joined_date'];
    $length = $request->input('length');
    $column = $request->input('column');
    $search_input = $request->input('search');
    $firstname = $request->query('firstname');
    $surname = $request->query('surname');
    $email = $request->query('email');

    $members_model= new members();
    $query = $members_model->select('id', 'firstname', 'surname', 'email', 'gender', 'joined_date');
    if (false == empty($column)) {
      $query->orderBy($columns[$column]);
    }
    if (false === empty($search_input)) {
      $query->where('id', 'like', '%' . $search_input . '%')
        ->orwhere('firstname', 'like', '%' . $search_input . '%')
        ->orwhere('surname', 'like', '%' . $search_input . '%')
        ->orWhere('email', 'like', '%' . $search_input . '%')
        ->orwhere('gender', 'like', '%' . $search_input . '%')
        ->orwhere('joined_date', 'like', '%' . $search_input . '%');
    }
    if ($firstname != '' ) {
      $query->where('firstname', 'LIKE', '%'.$firstname.'%');
    }
    if ($surname != '') {
      $query->where('surname', 'LIKE', '%'.$surname.'%');
    }
    if ($email != '') {
      $query->where('email', 'LIKE', '%'.$email.'%');
    };
    if (false == empty($length)) {
      $query->paginate($length);
    }
    $members = $query->get()->toJson();
    return $members;
  }
}