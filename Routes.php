<?php
    return [
        
		App\Core\Route::get('|^category\/([0-9]+)\/?$|',        'Category',          'show'),
		App\Core\Route::get('|^dessert\/([A-z\s]+)\/?$|',     'Dessert',           'show'),


        //Api routes
        App\Core\Route::get('|^api/categories\/([0-9]+)/?(?:\?[A-z-_]+=[A-z0-9-_]+(?:&[A-z-_]+=[A-z0-9-_]+)*)?$|',       'ApiCategory',              'show'),
        App\Core\Route::get('|^api/desserts\/([0-9]+)/?$|',                                                              'ApiDessert',               'show'),
        App\Core\Route::get('|^api/desserts\/?(?:\?[A-z-_]+=[A-z0-9-_]+(?:&[A-z-_]+=[A-z0-9-_]+)*)?$|',                  'ApiCategory',              'show'),
        App\Core\Route::get('|^api/categories\/?$|',                                                                     'ApiMain',                  'show'),
        App\Core\Route::get('|^api/ingredientsCategories/?$|',                                                           'ApiIngredient',            'getAllCategories'),
        App\Core\Route::get('|^api/ingredients\/([0-9]+)/?$|',                                                           'ApiIngredient',            'getIngredients'),
        App\Core\Route::get('|^api/ingredient\/([A-z\s]+)/?$|',                                                          'ApiIngredient',            'getIngredient'),

        App\Core\Route::post('|^api/register/?$|',                                                                       'ApiMain',                  'postRegister'),
        App\Core\Route::post('|^api/login/?$|',                                                                          'ApiMain',                  'postLogin'),

        
        //private
        App\Core\Route::get('|^api/user\/profile\/?$|',                  'ApiUserDashboard',                 'show'),
        App\Core\Route::post('|^api/postComment\/?$|',                   'ApiUserDashboard',                 'postComment'),
        App\Core\Route::post('|^api/postAssessment\/?$|',                'ApiUserDashboard',                 'postAssessment'),
        App\Core\Route::get('|^api/createDessert/?$|',                   'ApiUserDashboard',                 'addDessert'),
        App\Core\Route::post('|^api/createDessert/?$|',                  'ApiUserDashboard',                 'postAddDessert'),
        App\Core\Route::post('|^api/addDessertPhoto/?$|',                'ApiUserDashboard',                 'postDessertPhoto'),
        App\Core\Route::post('|^api/deleteDessert/?$|',                  'ApiUserDashboard',                 'deleteDessert'),

        App\Core\Route::any('|^api/.*$|',                                'ApiMain',                         'error'),
         
        


        //admin routes
        
        App\Core\Route::get('|^adminLogin12345$|',             'Main',          'getAdminLogin'),
        App\Core\Route::post('|^adminLogin12345$|',            'Main',          'postAdminLogin'),

        App\Core\Route::get('|^adminProfile$|',                'AdminDashboard',     'addDessert'),
        App\Core\Route::post('|^adminProfile$|',                'AdminDashboard',     'postAddDessert'),


        
        //default route
        App\Core\Route::any('|^.*$|',                         'Main',              'home')

    ];
?>