<?php

namespace Mini\Libs;

class Helper
{
    /**
     * debugPDO
     *
     * Shows the emulated SQL query in a PDO statement. What it does is just extremely simple, but powerful:
     * It combines the raw query and the placeholders. For sure not really perfect (as PDO is more complex than just
     * combining raw query and arguments), but it does the job.
     *
     * @author Panique
     * @param string $raw_sql
     * @param array $parameters
     * @return string
     */
    static public function debugPDO($raw_sql, $parameters) {

        $keys = array();
        $values = $parameters;

        foreach ($parameters as $key => $value) {

            // check if named parameters (':param') or anonymous parameters ('?') are used
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            // bring parameter into human-readable format
            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            } elseif (is_array($value)) {
                $values[$key] = implode(',', $value);
            } elseif (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        /*
        echo "<br> [DEBUG] Keys:<pre>";
        print_r($keys);

        echo "\n[DEBUG] Values: ";
        print_r($values);
        echo "</pre>";
        */

        $raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);

        return $raw_sql;
    }

	/**
	 * Converts dashed string to camel-case string
	 * @param string|string[] $urlPart
	 * @return string|string[]|null
	 */
	static public function dashToUpper($urlPart){
		return preg_replace_callback('#-([a-z])#', function ($matches){
			return strtoupper($matches[1]);
		},$urlPart);
	}

	/**
	 * Redirects to specified URL
	 * @param string $url
	 * @param int $statusCode
	 */
	static public function redirect(string $url, $statusCode = 303) {
		header('Location: '.$url, true, $statusCode);
		die();
	}

	/**
	 * Escape a string
	 * @param $string
	 * @return string escaped string
	 */
	public static function e($string){
		return htmlspecialchars($string, ENT_QUOTES);
	}

}
