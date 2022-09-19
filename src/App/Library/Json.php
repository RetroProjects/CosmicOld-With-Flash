<?php
namespace Cosmic\App\Library;

use Cosmic\App\Models\Permission;

class Json
{
    public static function encode($string)
    {
        echo json_encode($string, JSON_PRETTY_PRINT);
        exit;
    }
  
    public static function validate($string)
    {
        // decode the JSON data
        $result = json_decode($string);

        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
            response()->json(["status" => "error", "message" => $error]);
        }

        return true;
    }

    public static function filter($object, $sortOrder, $fieldOrder)
    {
        $data = $alldata = $object;
        $datatable = array_merge(['pagination' => [], 'sort' => [], 'query' => []], $_REQUEST);

        $filter = isset($datatable['query']['generalSearch']) && is_string($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        if (!empty($filter)) {
            $data = array_filter($data, function ($a) use ($filter) {
                return (boolean)preg_grep("/$filter/i", (array)$a);
            });
            unset($datatable['query']['generalSearch']);
        }

        // filter by field query
        $query = isset($datatable['query']) && is_array($datatable['query']) ? $datatable['query'] : null;
        if (is_array($query)) {
            $query = array_filter($query);
            foreach ($query as $key => $val) {
                $data = list_filter($data, [$key => $val]);
            }
        }

        $sort = !empty($datatable['sort']['sort']) ? $datatable['sort']['sort'] : $sortOrder;
        $field = !empty($datatable['sort']['field']) ? $datatable['sort']['field'] : $fieldOrder;
        $meta = [];
        $page = !empty($datatable['pagination']['page']) ? (int)$datatable['pagination']['page'] : 1;
        $perpage = !empty($datatable['pagination']['perpage']) ? (int)$datatable['pagination']['perpage'] : -1;
        $pages = 1;
        $total = count($data); // total items in array

        // sort
        usort($data, function ($a, $b) use ($sort, $field) {
            if (!isset($a->$field) || !isset($b->$field)) {
                return false;
            }
            if ($sort === 'asc') {
                return $a->$field > $b->$field;
            }
            return $a->$field <=> $b->$field;
        });

        // $perpage 0; get all data
        if ($perpage > 0) {
            $pages = ceil($total / $perpage); // calculate total pages
            $page = max($page, 1); // get 1 page when $_REQUEST['page'] <= 0
            $page = min($page, $pages); // get last page when $_REQUEST['page'] > $totalPages
            $offset = ($page - 1) * $perpage;
            if ($offset < 0) {
                $offset = 0;
            }
            $data = array_slice($data, $offset, $perpage, true);
        }

        $meta = [
            'page' => $page,
            'pages' => $pages,
            'perpage' => $perpage,
            'total' => $total,
        ];

        // if selected all records enabled, provide all the ids
        if (isset($datatable['requestIds']) && filter_var($datatable['requestIds'], FILTER_VALIDATE_BOOLEAN)) {
            $meta['rowIds'] = array_map(function ($row) {
                foreach ($row as $first) break;
                return $first;
            }, $alldata);
        }
        $result = [
            'meta' => $meta + [
                    'sort' => $sort,
                    'field' => $field,
                ],
            'data' => $data,
        ];

        self::encode($result);
    }
}
