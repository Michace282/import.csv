<?php

namespace Services;


class ParseCSV
{
    ## Читает CSV файл и возвращает данные в виде массива.
    ## @param string $file_path Путь до csv файла.
    ## string $col_delimiter Разделитель колонки (по умолчанию автоопределине)
    ## string $row_delimiter Разделитель строки (по умолчанию автоопределине)
    ## ver 6
    function parse( $file_path ){

        // open the CVS file
        $handle = @fopen( $file_path ,"r");
        if ( !$handle )
        {
            throw new \Exception( "Couldn't open $file_path!" );
        }
        $result = [];
        // read the first line
        $first = strtolower( fgets( $handle, 4096 ) );
        // get the keys
        $keys = str_getcsv( $first );
        // read until the end of file
        while ( ($buffer = fgets( $handle, 4096 )) !== false )
        {
            // read the next entry
            $array = str_getcsv ( $buffer );
            if ( empty( $array ) )
                continue;
            $row = [];
            $i=0;
            // replace numeric indexes with keys for each entry
            foreach ( $keys as $key )
            {
                $row[ $key ] = $array[ $i ]; $i++; }
            // add relational array to final result
            $result[] = $row;
        }
        fclose( $handle );
        return $result;
    }
}