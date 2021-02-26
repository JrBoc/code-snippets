<?php

Response::macro('success', function ($message = null, $data = [], $http_status = 200) {
    return $this->json([
        'message' => $message,
        'success' => true,
        'data' => $data
    ], $http_status);
});

Response::macro('failed', function ($message = null, $code = 0, $data = [], $http_code = 400) {
    return $this->json([
        'message' => $message,
        'success' => false,
        'code' => $code,
        'data' => $data,
    ], $http_code);
});

return response()->success('User created!', 201);

return response()->success('User updated!', 202);

return response()->success('Displaying users', $users);

return response()->failed('Error invalidation', 1, $validator->errors(), 422);
