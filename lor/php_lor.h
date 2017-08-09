#ifndef PHP_LOR_H
#define PHP_LOR_H

extern zend_module_entry lor_module_entry;
#define phpext_lor_ptr &lor_module_entry

#define PHP_LOR_VERSION "0.1.0" 

#ifdef PHP_WIN32
#       define PHP_LOR_API __declspec(dllexport)
#elif defined(__GNUC__) && __GNUC__ >= 4
#       define PHP_LOR_API __attribute__ ((visibility("default")))
#else
#       define PHP_LOR_API
#endif

#ifdef ZTS
#include "TSRM.h"
#endif


#ifdef ZTS
#define LOR_G(v) TSRMG(lor_globals_id, zend_lor_globals *, v)
#else
#define LOR_G(v) (lor_globals.v)
#endif

#endif
 
PHP_METHOD(lor,__construct);
PHP_METHOD(lor,__destruct);
PHP_METHOD(lor,strToNumber);
