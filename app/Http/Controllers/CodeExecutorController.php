<?php

namespace App\Http\Controllers;

// use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;

class CodeExecutorController extends Controller
{
    public static function runCppCode(array $param)
    {
        
        // Receive the C++ code and input from the request.
        $cppCode = $param['code'];
        $input = $param['input']; // Default to an empty string if 'input' is not provided.

        // Create a temporary input file and write the input data to it.
        $inputFilePath = tempnam(sys_get_temp_dir(), 'Code/cpp_input_');
        file_put_contents($inputFilePath, $input);

        // Create a temporary error file for compilation or execution errors.
        $errorFilePath = tempnam(sys_get_temp_dir(), 'Code/cpp_error_');
        $salt = random_int(1, 1000000);
        // Run the C++ code with input and capture the output and errors.
        $command = "echo '$cppCode' > /tmp/cpp_code_$salt.cpp && g++ /tmp/cpp_code_$salt.cpp -o /tmp/cpp_code_output_$salt 2> $errorFilePath && cat $inputFilePath | /tmp/cpp_code_output_$salt";
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            // Compilation or execution error occurred.
                // Clean up temporary files.
            $errorOutput = file_get_contents($errorFilePath);
            unlink($inputFilePath);
            unlink($errorFilePath);
            unlink("/tmp/cpp_code_$salt.cpp");
            // Return the output as a response.

            // Return the error message as a response.
            return ['error' => $errorOutput];
        }

        // Clean up temporary files.
        unlink($inputFilePath);
        // unlink($errorFilePath);
        unlink("/tmp/cpp_code_$salt.cpp");
        unlink("/tmp/cpp_code_output_$salt");
        // Return the output as a response.
        return ['output' => implode(PHP_EOL, $output)];
    }

    public static function runJavaCode(array $param)
    {
      
        $salt = random_int(0,1000000) ;
        // Receive the Java code and input from the request.
        $javaCode = $param['code'];
        $input = $param['input']; // Default to an empty string if 'input' is not provided.
        $errorFilePath = tempnam(sys_get_temp_dir(), 'java_error_');
        $inputFilePath = tempnam(sys_get_temp_dir(), 'java_input_');
        file_put_contents($inputFilePath, $input);

        $filename =  "java_code$salt.java";

        // Path to the /tmp directory
        $tmpDirectory = "/tmp/";
        // Write the text to a file in the /tmp directory
        file_put_contents($tmpDirectory . $filename, $param['code']);


        // Compile the Java code and capture the errors, if any.
        
        $compileCommand =  "javac /tmp/java_code$salt.java 2> $errorFilePath ";
        // return $compileCommand ; 

        exec($compileCommand, $compileOutput, $compileReturnCode);
        
        $compileCommand = "javac /tmp/java_code$salt.java 2> $errorFilePath" ;
        exec($compileCommand, $compileOutput, $compileReturnCode);
        if ($compileReturnCode !== 0) {
            // Compilation error occurred.
            $errorOutput = file_get_contents($errorFilePath);
            unlink($inputFilePath);
            unlink($errorFilePath);
            unlink("/tmp/java_code$salt.java");
            // Return the error message as a response.
            return ['error' => $errorOutput];
        }
        // Run the compiled Java code with input and capture the output.
        $executionCommand = "cd /tmp  && cat $inputFilePath | java Main" ;
        exec($executionCommand, $output, $executionReturnCode);
       

        // Clean up temporary files.
        unlink($inputFilePath);
        unlink($errorFilePath);
        unlink("/tmp/java_code$salt.java");
        unlink("/tmp/Main.class"); // Remove compiled .class file

        // Return the output as a response.
        return ['output' => implode(PHP_EOL, $output)];
    }

    Public static  function generateTestCases($model){
        $salt = random_int(0 , 1000000) ;
        $executionCommand = "cd /home/kassem/Projects/Graduated_Project/code/storage/app/public && echo \"$model\" | java Main" ;
        // return $executionCommand ;
        exec($executionCommand, $output, $executionReturnCode);
        return $output ; 
    }
    public function replaceWordInFile() {
        $filePath = 'Main.java';
        $searchWord = 'Main';
        $replaceWord = 'Kasseem';
        $content = Storage::get($filePath);
        return $content;
        $newContent = str_replace($searchWord, $replaceWord, $content);

        Storage::put($filePath, $newContent);
        return response()->json([
            'message' => 'word replaced succfully...'
        ]);

    }
}
