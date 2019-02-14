<?php

return [
    'auth' => [
        'json' => '/api/orbit/auth/default.ashx',
        'soap' => '/api/orbit/auth/default.asmx',
        'methods' => [
            'verifyAuthToken' => 'ents',
        ]
    ],
    'user' => [
        'json' => '/api/orbit/auth/user.ashx',
        'soap' => '/api/orbit/auth/user.asmx',
        'methods' => [
            'loginUser' => 'ents',
            'loginUserExt' => 'ents',
            'registerUser' => 'ents',
            'GetUserBasicAccountInfo' => 'ents',
            'forgotPassword' => 'ents',
            'getParentalControl' => 'ents',
        ]
    ],
    'library' => [
        'json' => '/api/orbit/library/uv.ashx',
        'soap' => '/api/orbit/library/uv.asmx',
        'methods' => [
            'getUserLibraryExtByOptions' => 'ents',
            'getLastLibraryUpdateTimeUTC' => 'ents',
            'getPurchasedTitle' => 'ents'
        ]
    ],
    'wishlist' => [
        'json' => '/api/orbit/wishlist/default.ashx',
        'soap' => '/api/orbit/wishlist/default.asmx',
        'methods' => [
            'getWishlist' => 'ents',
            'addItemToWishList' => 'ents',
            'removeItemFromWishList' => 'ents',
            'checkTitleAvailableInWishList' => 'ents',
            'checkUserWishListForItems' => 'ents',
        ]
    ],
    'utility' => [
        'json' => '/api/orbit/util/default.ashx',
        'soap' => '/api/orbit/util/default.asmx',
        'methods' => [
            'getEulaText' => 'ents',
            'getTermsOfService' => 'ents',
            'getPrivacyPolicy' => 'ents',
            'setupDevice' => 'ents',
            'getDeviceEnv' => 'ents',
            'acceptEula' => 'ents'
        ]
    ],
    'titledata' => [
        'json' => '/api/orbit/titledata/default.ashx',
        'soap' => '/api/orbit/titledata/default.svc',
        'methods' => [
            'getFullSummary' => 'ents',
            'getFullSummaryPlural' => 'ents',
            'getRegionalRatings' => 'rmh',
            'getShortSummary' => 'ents'
        ]
    ],
    'browse' => [
        'json' => '/api/orbit/browse/default.ashx',
        'soap' => '/api/orbit/browse/default.svc',
        'methods' => [
            'getBundleListing' => 'ents',
            'getBrowseList' => 'ents',
            'getNavigation' => 'ents'
        ]
    ],
    'search' => [
        'json' => 'ss',
        'soap' => '/api/orbit/search/default.svc',
        'methods' => [
            'searchTitleSetOptions' => 'ents',
        ]
    ],
    'commerce' => [
        'json' => '/api/orbit/commerce/default.ashx',
        'soap' => '/api/orbit/commerce/default.asmx',
        'methods' => [
            'checkIfTitleInLibrary' => 'ents',
            'applyGiftCode' => 'ents',
            'calcOrderTax' => 'ents',
            'doPurchase' => 'ents',
            'getBillingInfo' => 'ents',
        ]
    ],
    'download' => [
        'json' => '/api/orbit/download/uv.ashx',
        'soap' => '/api/orbit/download/uv.asmx',
        'methods' => [
            'pollForDownloads' => 'ents'
        ]
    ],
    'streaming' => [
        'json' => '/api/orbit/stream/default.ashx',
        'soap' => '/api/orbit/stream/default.asmx',
        'methods' => [

        ]
    ],
    'uv' => [
        'json' => '/api/orbit/auth/uv.ashx',
        'soap' => '/api/orbit/auth/uv.asmx',
        'methods' => [

        ]
    ]
];
