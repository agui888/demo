PHP_ARG_ENABLE(lor, whether to enable lor support,  [ --enable-lor           Enable lor support]) 
if test "$PHP_LOR" != "no"; then 
 PHP_SUBST(LOR_SHARED_LIBADD)
 PHP_NEW_EXTENSION(lor, lor.c, $ext_shared)
fi
