<?php

return array (
    // string (optional)
    // basepath to source files and react source
    'basepath' => '',
    
    // string (optional)
    // path (excluding basepath if provided) of react source file
    'react_src' => '',
    
    // array
    // paths (excluding basepath if provided) of source files
    'src_files' => [],
    
    // string (optional)
    // If you have namespaces in your JS application and React is inside an object,
    // provide the path in here.
    // Example: Your application access react by Application.libraries.React, you have
    // to pass Application.libraries
    'react_prefix' => '',
    
    // string (optional)
    // Like react_prefix, but for components.
    'components_prefix' => '',
);
