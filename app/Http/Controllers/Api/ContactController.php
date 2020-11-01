<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function getContacts(Request $request)
    {
        try {
            $name = $request->get('name');
            $companyName = $request->get('company');
            if ($name) {
                $rows = Contact::where('name', $name)->paginate(5);
                $rows->appends('name', $name);
            } else if ($companyName) {
                $rows = Contact::select('contacts.*')->join('companies', 'companies.id', '=', 'contacts.company_id')->where('companies.name', '=', $companyName)->paginate(5);
                $rows->appends('company', $companyName);
            } else {
                $rows = Contact::paginate(5);
            }

            $items = [];
            foreach ($rows as $row) {
                $itm = new \stdClass();
                $itm->id = $row->id;
                $itm->name = $row->name;
                $itm->phone = $row->phone;
                $itm->email = $row->email;
                $itm->note = $row->note;
                $itm->created_at = $row->created_at;
                $itm->updated_at = $row->updated_at;
                $itm->company = new \stdClass();
                $itm->company->id = $row->company->id;
                $itm->company->name = $row->company->name;
                $itm->company->created_at = $row->company->created_at;
                $itm->company->updated_at = $row->company->updated_at;

                $items[] = $itm;
            }

            return response()->json([
                'status' => true,
                'next_page_link' => $rows->nextPageUrl(),
                'max_page' => get_max_page_number($rows->total(), $rows->perPage()),
                'data' => $items,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     */
    public function getContactById(int $id)
    {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                $httpStatusException = 404;
                throw new \Exception('Contact not found');
            }

            $itm = new \stdClass();
            $itm->id = $contact->id;
            $itm->name = $contact->name;
            $itm->phone = $contact->phone;
            $itm->email = $contact->email;
            $itm->note = $contact->note;
            $itm->created_at = $contact->created_at;
            $itm->updated_at = $contact->updated_at;
            $itm->company = new \stdClass();
            $itm->company->id = $contact->company->id;
            $itm->company->name = $contact->company->name;
            $itm->company->created_at = $contact->company->created_at;
            $itm->company->updated_at = $contact->company->updated_at;

            return response()->json([
                'status' => true,
                'data' => $itm,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $httpStatusException);
        }

    }

    /**
     * @param int $id
     * @return Response
     */
    public function deleteContact(int $id)
    {
        $httpStatusException = 500;
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                $httpStatusException = 404;
                throw new \Exception('Contact not found');
            }

            if ($contact->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Deleted successfully.'
                ], 200);
            } else {
                throw new \Exception('There was an error to delete.');
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $httpStatusException);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function setContact(Request $request)
    {
        $httpStatusException = 500;
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'phone' => 'required|max:255',
                'email' => 'required|email|max:150',
                'company' => 'required|max:150',
            ]);

            if ($validator->fails()) {
                $errorMsg = [];
                foreach ($validator->errors()->getMessages() as $msgs) {
                    foreach ($msgs as $msg) {
                        $errorMsg[] = $msg;
                    }
                }
                $httpStatusException = 400;
                throw new \Exception(implode(', ', $errorMsg));
            }

            $name = $request->input('name');
            $phone = $request->input('phone');
            $email = $request->input('email');
            $companyName = $request->input('company');
            $note = $request->input('note');

            $company = Company::where('name', $companyName)->first();
            if (!$company) {
                $company = new Company();
                $company->name = $companyName;
                if (!$company->save()) {
                    throw new \Exception('Error to save Company.');
                }
            }

            $contact = new Contact();
            $contact->name = $name;
            $contact->phone = $phone;
            $contact->email = $email;
            $contact->company_id = (int)$company->id;
            if ($note) {
                $contact->note = $note;
            }

            if ($contact->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Saved successfully.'
                ], 200);
            } else {
                throw new \Exception('There was an error to save.');
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $httpStatusException);
        }

    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editContact(Request $request, int $id)
    {
        $httpStatusException = 500;
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'max:255',
                'phone' => 'max:255',
                'email' => 'email|max:150',
                'company' => 'max:150',
            ]);

            if ($validator->fails()) {
                $errorMsg = [];
                foreach ($validator->errors()->getMessages() as $msgs) {
                    foreach ($msgs as $msg) {
                        $errorMsg[] = $msg;
                    }
                }
                $httpStatusException = 400;
                throw new \Exception(implode(', ', $errorMsg));
            }

            $contact = Contact::find($id);
            if (!$contact) {
                $httpStatusException = 404;
                throw new \Exception('Contact not found');
            }

            $name = $request->input('name');
            if ($name) {
                $contact->name = $name;
            }

            $phone = $request->input('phone');
            if ($phone) {
                $contact->phone = $phone;
            }

            $email = $request->input('email');
            if ($email) {
                $contact->email = $email;
            }

            $companyName = $request->input('company');
            if ($companyName) {
                $company = Company::where('name', $companyName)->first();
                if (!$company) {
                    $company = new Company();
                    $company->name = $companyName;
                    if (!$company->save()) {
                        throw new \Exception('Error to save Company.');
                    }
                }
                $contact->company_id = (int)$company->id;
            }

            $note = $request->input('note');
            if ($note) {
                $contact->note = $note;
            }

            if ($contact->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Updated successfully . '
                ], 200);
            } else {
                throw new \Exception('There was an error to update . ');
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $httpStatusException);
        }

    }

}
