<?php
namespace Cosmic\System\Handlers;

use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;

use Exception;

class ExceptionHandler implements IExceptionHandler
{
    public function handleError(Request $request, Exception $error) : void
    {
        if ($request->getUrl()->contains('/housekeeping/api')) {

            response()->json([
                'error' => $error->getMessage(),
                'code'  => $error->getCode(),
            ]);

        }

        if($error instanceof NotFoundHttpException) {

            response()->httpCode(404);
            $request->setRewriteUrl(url('lost'));
            return;

        }

        throw $error;
    }
}
