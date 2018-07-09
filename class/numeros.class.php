<?php

class NumeroALetras
{
    private static $unidades = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];

    private static $decenas = [
        'VENTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];

    private static $centenas = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static function convertir($numero, $moneda = 'Quetzales', $centimos = 'centavos', $forzarCentimos = TRUE)
    {
        $converted = '';
        $decimales = '';
        $numero    = str_replace(',', '', $numero );

        if (($numero < 0) || ($numero > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }

        $div_decimales = explode( '.', $numero );

        if(count($div_decimales) > 1){
            $numero = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2)
            {
                $decNumberStrFill = str_pad( $decNumberStr, 9, '0', STR_PAD_LEFT );
                $decCientos       = substr( $decNumberStrFill, 6 );
                $decimales        = self::convertGroup( $decCientos );
            }
        }
        elseif (count($div_decimales) == 1 && $forzarCentimos)
            $decimales = 'CERO ';

        $numeroStr     = (string)$numero;
        $numeroStrFill = str_pad( $numeroStr, 9, '0', STR_PAD_LEFT );
        $millones      = substr( $numeroStrFill, 0, 3 );
        $miles         = substr( $numeroStrFill, 3, 3 );
        $cientos       = substr( $numeroStrFill, 6 );

        if (intval($millones) > 0) {
            if ($millones == '001')
                $converted .= 'UN MILLON ';
            elseif (intval($millones) > 0)
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));

        }

        if (intval($miles) > 0) {
            if ($miles == '001')
                $converted .= 'MIL ';
            elseif (intval($miles) > 0)
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001')
                $converted .= 'UN ';
            elseif (intval($cientos) > 0)
                $converted .= sprintf('%s ', self::convertGroup($cientos));
        }

        $cantidadLetras = $converted . strtoupper( $moneda );

        if( !empty($decimales) AND $decimales != 'CERO ' AND $decimales != 'CERO' )
            $cantidadLetras .= ' CON ' . $decimales . ' ' . strtoupper($centimos) ;
        
        $cantidadLetras .= ' EXACTOS';

        return $cantidadLetras;
    }


    private static function convertGroup($n)
    {
        $output = '';

        if ($n == '100')
            $output = "CIEN ";
        elseif ($n[0] !== '0')
            $output = self::$centenas[$n[0] - 1];

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= self::$unidades[$k];
        }
        else {
            if(($k > 30) && ($n[2] !== '0'))
                $output .= sprintf('%sY %s', self::$decenas[intval($n[1]) - 2], self::$unidades[intval($n[2])]);
            else
                $output .= sprintf('%s%s', self::$decenas[intval($n[1]) - 2], self::$unidades[intval($n[2])]);
        }

        return $output;
    }
}

?>