<?php

namespace App\SRC\Helpers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class UserHelper {
    public function create($attributes) {
        DB::beginTransaction();
        try {
            if(!$this->validMobile($attributes)) {
                if(!$this->validEmail($attributes)) {
                    if($user = $this->newUser($attributes)) {
                        /*  Mail to Admin after new user registration successfully */
                        DB::commit();
                        return $user;
                    }
                    throw new Exception("Failed to create user, please try again");
                }
                throw new Exception("Email already in use, please try again");
            }
            throw new Exception("Mobile number already in use. please try again");
        } catch(\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }
    private function validMobile($attributes, int $id = 0) {
        if ($id) {
            return optional(User::query()->where('id', '!=', $id)->where('mobile', $attributes['mobile']))->count();
        }
        return optional(User::query()->where('mobile', $attributes['mobile']))->count();
    }

    private function validEmail($attributes, int $id = 0) {
        if ($id) {
            return optional(User::query()->where('id','!=',  $id)->where('email', $attributes['email']))->count();
        }
        return optional(User::query()->where('email', $attributes['email']))->count();
    }

    private function newUser($attributes) {
        return User::query()->create([
            'name'          => $attributes['first_name']    .' '.   $attributes['last_name'],
            'email'         => $attributes['email'],
            'mobile'        => $attributes['mobile'],
            'password'      => Hash::make('secret'),
            'user_type'     => 10, /* Note: `user_type = 10` for documents view only */
            'first_name'    => $attributes['first_name'],
            'last_name'     => $attributes['last_name'],
            'status'        => 1,
        ]);
    }

    public function update($attributes, $id) {
        DB::beginTransaction();
        try {
            if ($this->checkUser($id)) {
                if (!$this->validMobile($attributes, $id)) {
                    if (!$this->validEmail($attributes, $id)) {
                        if ($user = $this->editUser($attributes, $id)) {
                            DB::commit();
                            return $user;
                        }
                        throw new Exception('Failed to update user, please try again');
                    }
                    throw new Exception('Email already in use, please try again');
                }
                throw new Exception('Mobile already in use, please try again');
            }
            throw new Exception('Oops, User not found');
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    private function editUser($attributes, $id) {
        $user = User::query()->where('id', $id)->first();
        $user->name         = $attributes['first_name']. ' ' .$attributes['last_name'];
        $user->first_name   = $attributes['first_name'];
        $user->last_name    = $attributes['last_name'];
        $user->mobile       = $attributes['mobile'];
        $user->email        = $attributes['email'];
        $user->save();
        return $user;
    }

    private function checkUser($id) {
        return optional(User::query()->where('id', $id))->first();
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            if ($this->checkUser($id)) {
                if ($count = $this->deleteUser($id)) {
                    DB::commit();
                    return $count;
                }
                throw new Exception('Failed to delete user, please try again');
            }
            throw new Exception('Oops, user not found');
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    private function deleteUser($id) {
        return User::query()->where('id', $id)->delete();
    }
}
