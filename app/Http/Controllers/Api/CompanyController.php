<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends BaseController
{

    /**
     * @return Response
     */
    public function getCompanies()
    {
        try {
            $rows = Company::paginate(5);

            $items = [];
            foreach ($rows as $row) {
                $itm = new \stdClass();
                $itm->id = $row->id;
                $itm->name = $row->name;
                $itm->total_contacts = count($row->contacts);
                $itm->created_at = $row->created_at;
                $itm->updated_at = $row->updated_at;

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
     * @return Response
     */
    public function getCompaniesAll()
    {
        try {
            $rows = Company::all();

            $items = [];
            foreach ($rows as $row) {
                $itm = new \stdClass();
                $itm->id = $row->id;
                $itm->name = $row->name;
                $itm->created_at = $row->created_at;
                $itm->updated_at = $row->updated_at;

                $itm->contacts = [];
                foreach ($row->contacts as $con) {
                    $itmC = new \stdClass();
                    $itmC->id = $con->id;
                    $itmC->name = $con->name;
                    $itmC->phone = $con->phone;
                    $itmC->email = $con->email;
                    $itmC->note = $con->note;
                    $itmC->created_at = $con->created_at;
                    $itmC->updated_at = $con->updated_at;
                    $itm->contacts[] = $itmC;
                }

                $items[] = $itm;
            }

            return response()->json([
                'status' => true,
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
    public function deleteCompany(int $id)
    {
        $httpStatusException = 500;
        try {
            $company = Company::find($id);
            if (!$company) {
                $httpStatusException = 404;
                throw new \Exception('Company not found');
            }

            if ($company->delete()) {
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
    public function setCompany(Request $request)
    {
        $httpStatusException = 500;
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:150',
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

            $company = new Company();
            $company->name = $name;

            if ($company->save()) {
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
    public function editCompany(Request $request, int $id)
    {
        $httpStatusException = 500;
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'max:150',
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

            $company = Company::find($id);
            if (!$company) {
                $httpStatusException = 404;
                throw new \Exception('Company not found');
            }

            $name = $request->input('name');
            if ($name) {
                $company->name = $name;
            }


            if ($company->save()) {
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
