<?php declare(strict_types=1);
namespace ProfilerPack\Service;

// This product includes Xdebug, freely available from http://xdebug.org/

class XdebugTraceFileParser
{
    protected $handle;

    /**
     * Stores the last function, time and memory for the entry point per
     * stack depth. int=>array(string, float, int).
     */
    protected $stack;

    /**
     * Stores per function the total time and memory increases and calls
     * string=>array(float, int, int)
     */
    protected $functions;

    /**
     * Stores which functions are on the stack
     */
    protected $stackFunctions;

    public function __construct( $fileName )
    {
        $this->handle = fopen( $fileName, 'r' );
        if ( !$this->handle )
        {
            throw new \Exception( "Can't open '$fileName'" );
        }
        $this->stack[-1] = ['', 0, 0, 0, 0];
        $this->stack[ 0] = ['', 0, 0, 0, 0];

        $this->stackFunctions = [];
        $header1 = fgets( $this->handle );
        $header2 = fgets( $this->handle );
        if ( !preg_match( '@Version: [23].*@', $header1 ) || !preg_match( '@File format: [2-4]@', $header2 ) )
        {
            throw new \Exception( "This file is not an Xdebug trace file made with format option '1' and version 2 to 4.");
        }
    }

    public function parse()
    {
        $c = 0;
        $size = fstat( $this->handle );
        $size = $size['size'];
        $read = 0;

        while ( !feof( $this->handle ) )
        {
            $buffer = fgets( $this->handle, 4096 );
            $read += strlen( $buffer );
            $this->parseLine( $buffer );
            $c++;

//            if ( $c % 25000 === 0 )
//            {
//                printf( " (%5.2f%%)\n", ( $read / $size ) * 100 );
//            }
        }
    }

    private function parseLine( $line )
    {
        /*
            if ( preg_match( '@^Version: (.*)@', $line, $matches ) )
            {
            }
            else if ( preg_match( '@^File format: (.*)@', $line, $matches ) )
            {
            }
            else if ( preg_match( '@^TRACE.*@', $line, $matches ) )
            {
            }
            else // assume a normal line
            */
        {
            $parts = explode( "\t", $line );

            if (count( $parts ) < 5)
            {
                return;
            }

            $depth = $parts[0];
            $funcNr = $parts[1];
            $time = (float)$parts[3];
            $memory = (int)$parts[4];
            if ( $parts[2] == '0' ) // function entry
            {
                $funcName = $parts[5];
                $intFunc = $parts[6];

                $this->stack[$depth] = [$funcName, $time, $memory, 0, 0];

                array_push( $this->stackFunctions, $funcName );
            }
            else if ( $parts[2] == '1') // function exit
            {
                if(!isset($this->stack[$depth]) || !isset($this->stack[$depth-1])) {
                    return;
                }

                list( $funcName, $prevTime, $prevMem, $nestedTime, $nestedMemory ) = $this->stack[$depth];

                // collapse data onto functions array
                $dTime   = $time   - $prevTime;
                $dMemory = $memory - $prevMem;

                $this->stack[$depth - 1][3] += $dTime;
                $this->stack[$depth - 1][4] += $dMemory;

                array_pop( $this->stackFunctions );

                $this->addToFunction( $funcName, $dTime, $dMemory, $nestedTime, $nestedMemory );
            }
        }
    }

    protected function addToFunction( $function, $time, $memory, $nestedTime, $nestedMemory )
    {
        if ( !isset( $this->functions[$function] ) )
        {
            $this->functions[$function] = [0, 0, 0, 0, 0];
        }

        $elem = &$this->functions[$function];
        $elem[0]++;
        if ( !in_array( $function, $this->stackFunctions ) ) {
            $elem[1] += $time;
            $elem[2] += $memory;
            $elem[3] += $nestedTime;
            $elem[4] += $nestedMemory;
        }
    }

    public function getFunctions( $sortKey = null )
    {
        $result = [];
        foreach ( $this->functions as $name => $function )
        {
            $result[$name] = [
                'calls'                 => $function[0],
                'time-inclusive'        => $function[1],
                'memory-inclusive'      => $function[2],
                'time-children'         => $function[3],
                'memory-children'       => $function[4],
                'time-own'              => $function[1] - $function[3],
                'memory-own'            => $function[2] - $function[4]
            ];
        }

        if ( $sortKey !== null )
        {
            uasort( $result,
                function( $a, $b ) use ( $sortKey )
                {
                    return ( $a[$sortKey] > $b[$sortKey] ) ? -1 : ( $a[$sortKey] < $b[$sortKey] ? 1 : 0 );
                }
            );
        }

        return $result;
    }
}
