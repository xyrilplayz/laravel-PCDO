<?php

namespace App\Http\Controllers;

use App\Models\CoopMember;
use Illuminate\Http\Request;

class CoopMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CoopMember $coopMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoopMember $coopMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoopMember $coopMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoopMember $coopMember)
    {
        //
    }

    public function search(Request $request)
    {
        //
    }

    public function export()
    {
        //
    }

    public function import(Request $request)
    {
        //
    }

    public function fullName(CoopMember $coopMember)
    {
        return trim("{$coopMember->first_name} {$coopMember->middle_name} {$coopMember->last_name} {$coopMember->suffix}");
    }
}