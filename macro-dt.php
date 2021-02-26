<?php

/**
 * Marcos
 */

DataTableAbstract::macro('searchFilter', function ($columns = [], $form = [], callable $callback = null) {
    return $this->filter(function ($query) use ($columns, $form, $callback) {
        if (!is_null($form) && !is_null($columns)) {
            if (!is_null($form['search']) && isset($columns[$form['column']])) {
                if (is_array($columns[$form['column']])) {
                    $query->whereHas($columns[$form['column']][0], function ($q) use ($form, $columns) {
                        if (strpos($columns[$form['column']][1], '?') != false) {
                            $q->whereRaw($columns[$form['column']][1], ['%' . $form['search'] . '%']);
                        } else {
                            $q->where($columns[$form['column']][1], 'LIKE', '%' . $form['search'] . '%');
                        }
                    });
                } else {
                    if (strpos($columns[$form['column']], '?') != false) {
                        $query->whereRaw($columns[$form['column']], '%' . $form['search'] . '%');
                    } else {
                        $query->where($columns[$form['column']], 'LIKE', '%' . $form['search'] . '%');
                    }
                }
            }
        }

        if (is_callable($callback)) {
            call_user_func($callback, $this->resolveCallbackParameter());
        }
    });
});

/**
 * Usage
 */
$date_columns = [
    1 => 'date_sent',
    2 => 'date_received',
    3 => 'date_applied'
];

$columns = [
    1 => [
        'registration', // relationship
        'concat(fname, \' \', lname) LIKE ?' // column
    ],
    2 => [
        'registration', // relationship
        'email' // column
    ],
    3 => [
        'registration.agent',
        'name',
    ]
];

$form = [
    'status' => $request->form['status'] ?? null,
    'payment_status' => $request->form['payment_status'] ?? null,
    'search' => $request->form['search'] ?? null,
    'column' => $request->form['column'] ?? null,
    'date_column' => $request->form['date_column'] ?? null,
    'date_range' => $request->form['date_range'] ?? null,
];

return \datatables($query)
    ->searchFilter($columns, $form, function ($query) use ($form, $date_columns) {
        if (!is_null($form['payment_status'])) {
            $query->where('payment_status', $form['payment_status']);
        }

        if (!is_null($form['status'])) {
            $query->where('application_status', $form['status']);
        }

        if (!\is_null($form['date_range'])) {
            if (isset($date_columns[$form['date_column']])) {
                $query->whereBetween($date_columns[$form['date_column']], \explode(' - ', $form['date_range']));
            }
        }
    })
    ->toJson();

/**
 * Usage 2
 */

$columns = [
    1 => 'id',
    2 => 'name',
];

$form = [
    'search' => $request->form['search'] ?? null,
    'column' => $request->form['column'] ?? null,
];

return \datatables($query)
    ->searchFilter($columns, $form)
    ->toJson();
