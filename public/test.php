<?php

$path = getcwd();
echo "This Is Your Absolute Path: ";
echo $path;
  $host_name = 'db5014377318.hosting-data.io';
  $database = 'dbs11957396';
  $user_name = 'dbu2760645';
  $password = 'U527337335_chathaandco';

  $link = new mysqli($host_name, $user_name, $password, $database);

  if ($link->connect_error) {
    die('<p>Failed to connect to MySQL: '. $link->connect_error .'</p>');
  } else {
    echo '<p>Connection to MySQL server successfully established.</p>';
  }

  die();
?>

<?php
$json = '{
    "api_request": {
        "error": {},
        "resources": [
            "document"
        ],
        "status": "success",
        "status_code": 201,
        "url": "https://api.mindee.net/v1/products/mindee/invoices/v2/predict"
    },
    "document": {
        "id": "30df2aef-efd9-4127-b6be-60591f897088",
        "inference": {
            "extras": {},
            "finished_at": "2023-08-31T11:50:22.529678",
            "is_rotation_applied": true,
            "pages": [
                {
                    "extras": {},
                    "id": 0,
                    "orientation": {
                        "value": 0
                    },
                    "prediction": {
                        "company_registration": [],
                        "date": {
                            "confidence": 0.99,
                            "polygon": [
                                [
                                    0.208,
                                    0.34
                                ],
                                [
                                    0.34,
                                    0.34
                                ],
                                [
                                    0.34,
                                    0.355
                                ],
                                [
                                    0.208,
                                    0.355
                                ]
                            ],
                            "value": "2021-12-08"
                        },
                        "document_type": {
                            "value": "INVOICE"
                        },
                        "due_date": {
                            "confidence": 0.99,
                            "polygon": [
                                [
                                    0.613,
                                    0.312
                                ],
                                [
                                    0.76,
                                    0.312
                                ],
                                [
                                    0.76,
                                    0.329
                                ],
                                [
                                    0.613,
                                    0.329
                                ]
                            ],
                            "raw": null,
                            "value": "2021-12-31"
                        },
                        "invoice_number": {
                            "confidence": 0.99,
                            "polygon": [
                                [
                                    0.186,
                                    0.313
                                ],
                                [
                                    0.264,
                                    0.313
                                ],
                                [
                                    0.264,
                                    0.328
                                ],
                                [
                                    0.186,
                                    0.328
                                ]
                            ],
                            "value": "012345"
                        },
                        "locale": {
                            "confidence": 0.85,
                            "currency": "USD",
                            "language": "en"
                        },
                        "orientation": {
                            "confidence": 0.99,
                            "degrees": 0
                        },
                        "payment_details": [],
                        "supplier": {
                            "confidence": 0.86,
                            "polygon": [
                                [
                                    0.074,
                                    0.159
                                ],
                                [
                                    0.22,
                                    0.159
                                ],
                                [
                                    0.22,
                                    0.177
                                ],
                                [
                                    0.074,
                                    0.177
                                ]
                            ],
                            "value": "SKILL MENTOR"
                        },
                        "taxes": [],
                        "total_excl": {
                            "confidence": 0.96,
                            "polygon": [
                                [
                                    0.855,
                                    0.744
                                ],
                                [
                                    0.938,
                                    0.744
                                ],
                                [
                                    0.938,
                                    0.763
                                ],
                                [
                                    0.855,
                                    0.763
                                ]
                            ],
                            "value": 1210.0
                        },
                        "total_incl": {
                            "confidence": 0.98,
                            "polygon": [
                                [
                                    0.855,
                                    0.791
                                ],
                                [
                                    0.938,
                                    0.791
                                ],
                                [
                                    0.938,
                                    0.811
                                ],
                                [
                                    0.855,
                                    0.811
                                ]
                            ],
                            "value": 1210.0
                        }
                    }
                }
            ],
            "prediction": {
                "company_registration": [],
                "date": {
                    "confidence": 0.99,
                    "page_id": 0,
                    "polygon": [
                        [
                            0.208,
                            0.34
                        ],
                        [
                            0.34,
                            0.34
                        ],
                        [
                            0.34,
                            0.355
                        ],
                        [
                            0.208,
                            0.355
                        ]
                    ],
                    "value": "2021-12-08"
                },
                "document_type": {
                    "value": "INVOICE"
                },
                "due_date": {
                    "confidence": 0.99,
                    "page_id": 0,
                    "polygon": [
                        [
                            0.613,
                            0.312
                        ],
                        [
                            0.76,
                            0.312
                        ],
                        [
                            0.76,
                            0.329
                        ],
                        [
                            0.613,
                            0.329
                        ]
                    ],
                    "raw": null,
                    "value": "2021-12-31"
                },
                "invoice_number": {
                    "confidence": 0.99,
                    "page_id": 0,
                    "polygon": [
                        [
                            0.186,
                            0.313
                        ],
                        [
                            0.264,
                            0.313
                        ],
                        [
                            0.264,
                            0.328
                        ],
                        [
                            0.186,
                            0.328
                        ]
                    ],
                    "value": "012345"
                },
                "locale": {
                    "confidence": 0.85,
                    "currency": "USD",
                    "language": "en"
                },
                "payment_details": [],
                "supplier": {
                    "confidence": 0.86,
                    "page_id": 0,
                    "polygon": [
                        [
                            0.074,
                            0.159
                        ],
                        [
                            0.22,
                            0.159
                        ],
                        [
                            0.22,
                            0.177
                        ],
                        [
                            0.074,
                            0.177
                        ]
                    ],
                    "value": "SKILL MENTOR"
                },
                "taxes": [],
                "total_excl": {
                    "confidence": 0.96,
                    "page_id": 0,
                    "polygon": [
                        [
                            0.855,
                            0.744
                        ],
                        [
                            0.938,
                            0.744
                        ],
                        [
                            0.938,
                            0.763
                        ],
                        [
                            0.855,
                            0.763
                        ]
                    ],
                    "value": 1210.0
                },
                "total_incl": {
                    "confidence": 0.98,
                    "page_id": 0,
                    "polygon": [
                        [
                            0.855,
                            0.791
                        ],
                        [
                            0.938,
                            0.791
                        ],
                        [
                            0.938,
                            0.811
                        ],
                        [
                            0.855,
                            0.811
                        ]
                    ],
                    "value": 1210.0
                }
            },
            "processing_time": 0.811,
            "product": {
                "features": [
                    "locale",
                    "invoice_number",
                    "date",
                    "due_date",
                    "total_incl",
                    "total_excl",
                    "taxes",
                    "document_type",
                    "payment_details",
                    "company_registration",
                    "supplier",
                    "orientation"
                ],
                "name": "mindee/invoices",
                "type": "standard",
                "version": "2.4"
            },
            "started_at": "2023-08-31T11:50:21.718889"
        },
        "n_pages": 1,
        "name": "Training-and-tutoring-1-564x804.jpg"
    }
}'; // The JSON string you provided

$data = json_decode($json, true);

$invoiceData = $data['document']['inference']['pages'][0]['prediction'];

$date = $invoiceData['date']['value'];
$dueDate = $invoiceData['due_date']['value'];
$invoiceNumber = $invoiceData['invoice_number']['value'];
$supplier = $invoiceData['supplier']['value'];
$totalExcl = $invoiceData['total_excl']['value'];
$totalIncl = $invoiceData['total_incl']['value'];

// Now you can use these variables as needed
echo "Date: $date<br>";
echo "Due Date: $dueDate<br>";
echo "Invoice Number: $invoiceNumber<br>";
echo "Supplier: $supplier<br>";
echo "Total Excl: $totalExcl<br>";
echo "Total Incl: $totalIncl<br>";