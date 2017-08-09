#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_lor.h"
 
/////////////////////////////////start/////////////////////////////////////////
#define hashsize(n) ((ub4)1<<(n))
#define hashmask(n) (hashsize(n)-1)
#define HASHPOWER  20

typedef  unsigned long  int  ub4;
typedef  unsigned       char ub1;   /* unsigned 1-byte quantities */

#define mix(a,b,c) \
{ \
  a -= b; a -= c; a ^= (c>>13); \
  b -= c; b -= a; b ^= (a<<8); \
  c -= a; c -= b; c ^= (b>>13); \
  a -= b; a -= c; a ^= (c>>12);  \
  b -= c; b -= a; b ^= (a<<16); \
  c -= a; c -= b; c ^= (b>>5); \
  a -= b; a -= c; a ^= (c>>3);  \
  b -= c; b -= a; b ^= (a<<10); \
  c -= a; c -= b; c ^= (b>>15); \
}

ub4 hash_strToNumber( k, length, initval)
     register ub1 *k;        /* the key */
     register ub4  length;   /* the length of the key */
     register ub4  initval;  /* the previous hash, or an arbitrary value */
{
    register ub4 a,b,c,len;

    /* Set up the internal state */
    len = length;
    a = b = 0x9e3779b9;  /* the golden ratio; an arbitrary value */
    c = initval;         /* the previous hash value */

    /*---------------------------------------- handle most of the key */
    while (len >= 12)
        {
            a += (k[0] +((ub4)k[1]<<8) +((ub4)k[2]<<16) +((ub4)k[3]<<24));
            b += (k[4] +((ub4)k[5]<<8) +((ub4)k[6]<<16) +((ub4)k[7]<<24));
            c += (k[8] +((ub4)k[9]<<8) +((ub4)k[10]<<16)+((ub4)k[11]<<24));
            mix(a,b,c);
            k += 12; len -= 12;
        }

    /*------------------------------------- handle the last 11 bytes */
    c += length;
    switch(len)              /* all the case statements fall through */
        {
        case 11: c+=((ub4)k[10]<<24);
        case 10: c+=((ub4)k[9]<<16);
        case 9 : c+=((ub4)k[8]<<8);
            /* the first byte of c is reserved for the length */
        case 8 : b+=((ub4)k[7]<<24);
        case 7 : b+=((ub4)k[6]<<16);
        case 6 : b+=((ub4)k[5]<<8);
        case 5 : b+=k[4];
        case 4 : a+=((ub4)k[3]<<24);
        case 3 : a+=((ub4)k[2]<<16);
        case 2 : a+=((ub4)k[1]<<8);
        case 1 : a+=k[0];
            /* case 0: nothing left to add */
        }
    mix(a,b,c);
    /*-------------------------------------------- report the result */
    return c;
}
/////////////////////////////////end/////////////////////////////////////////
zend_class_entry *lor_ce;

ZEND_BEGIN_ARG_INFO(setName_args, 0)
    ZEND_ARG_INFO(0, name)
ZEND_END_ARG_INFO()
 
  
PHP_METHOD(lor,__construct){

}

PHP_METHOD(lor,__destruct){

}

PHP_METHOD(lor, strToNumber)
{
	char *str = NULL;
	int str_len = 0;
	//char *res = NULL;
	//res = malloc(200);
    if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s", &str, &str_len) == FAILURE) {  //获得函数传递的参数
    	RETURN_NULL();
    }

    if (str == NULL)
    {
    	RETURN_NULL();
    }
    unsigned long int hv = hash_strToNumber(str, strlen(str), 0) & hashmask(HASHPOWER);
    RETURN_LONG(hv);
    return;
    //sprintf(res, "%ul", hv);
    //RETURN_STRING(res, 1);
}

/*
*  声明类的方法
*/   
const zend_function_entry lor_functions[] = {
    PHP_ME(lor, __construct, NULL, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR) //ZEND_ACC_PUBLIC表示public权限  ZEND_ACC_CTOR表示构造函数
    PHP_ME(lor, __destruct, NULL, ZEND_ACC_PUBLIC|ZEND_ACC_DTOR) 
    PHP_ME(lor, strToNumber, setName_args, ZEND_ACC_PUBLIC)
    {NULL, NULL, NULL}   
};

/*
*  模块第一次加载时被调用
*/  
PHP_MINIT_FUNCTION(lor)
{
	zend_class_entry lor;
    INIT_CLASS_ENTRY(lor, "Lor", lor_functions); 
    lor_ce = zend_register_internal_class_ex(&lor, NULL, NULL TSRMLS_CC);
    zend_declare_class_constant_stringl(lor_ce, ZEND_STRL("version"), ZEND_STRL("0.01") TSRMLS_CC);
	return SUCCESS;
}

/* 
 每次请求前调用
*/  
PHP_RINIT_FUNCTION(lor)
{
	return SUCCESS;
}

/* 
 每次请求结束时调用
*/  
PHP_RSHUTDOWN_FUNCTION(lor)
{
	return SUCCESS;
} 

/*
 模块关闭时调用
*/
PHP_MSHUTDOWN_FUNCTION(lor)
{
	return SUCCESS;
}
 
/* 
 phpinfo()输出扩展信息
*/  
PHP_MINFO_FUNCTION(lor)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "lor support", "enabled");
	php_info_print_table_header(2, "The Version", "1.0.2");
	php_info_print_table_end(); 
}

/*
 模块结构，声明了startup\shutdown、模块名及phpinfo打印时的函数
*/  
zend_module_entry lor_module_entry = {
	STANDARD_MODULE_HEADER,
	"lor",
	NULL,
	PHP_MINIT(lor),
	PHP_MSHUTDOWN(lor),
	PHP_RINIT(lor),		/* 替换为NULL如果有请求开始 */
	PHP_RSHUTDOWN(lor),	/* 替换为 NULL 如果没有请求结束 */
	PHP_MINFO(lor),
	PHP_LOR_VERSION,
	STANDARD_MODULE_PROPERTIES
};
 
#ifdef COMPILE_DL_LOR
ZEND_GET_MODULE(lor)
#endif
