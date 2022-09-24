<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ListingController extends Controller
{
    // show all listings
    public function index(){

        return view('listings.index',[
            'heading'=>'latets listings',
            'listings'=> Listing::latest()->filter(request(['tag','search']))->simplePaginate(6)

        ]);

    }

    // show singel listing
    public function show(Listing $listing){

        return view('listings.show',[
            'listing'=> $listing
        ]);


    }
    //show create form
    public function create(){
        return view('listings.create');
    }


    //store listing Data
    public function store(Request $request){
        $formFields = $request->validate([
            'title'=>'required',
            'company'=>['required', Rule::unique('listings','company')],
            'location'=>'required',
            'website'=>'required',
            'email'=>['required','email'],
            'tags'=>'required',
            'description'=>'required'

        ]);
        if($request->hasFile('logo')){
            $formFields['logo']=$request->file('logo')->store('logos','public');
        }



        $formFields['user_id']= auth()->id();
        Listing::create($formFields);

         return redirect('/')->with('message' ,'listing created successfully');
    }

    // show edit form
    public function edit(Listing $listing)
    {
        return view('listings.edit' ,['listing' => $listing]);
    }

      //update listing Data
      public function update(Request $request ,Listing $listing){
       // make sure logged in user is owner
       if($listing->user_id !=auth()->id()){
        abort(403,'Unoauthorized Action');
       }




        $formFields = $request->validate([
            'title'=>'required',
            'company'=>'required',
            'location'=>'required',
            'website'=>'required',
            'email'=>['required','email'],
            'tags'=>'required',
            'description'=>'required'

        ]);
        if($request->hasFile('logo')){
            $formFields['logo']=$request->file('logo')->store('logos','public');
        }
        $listing->update($formFields);

         return back()->with('message' ,'listing updated successfully');
    }
    // delte listing
    public function destroy(Listing $listing){
        if($listing->user_id !=auth()->id()){
            abort(403,'Unoauthorized Action');
           }


        $listing->delete();
        return redirect('/')->with('message' ,'listing deleted successfully');

    }

}
