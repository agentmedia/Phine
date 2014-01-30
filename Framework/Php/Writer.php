<?php
namespace Phine\Framework\System\Php;

class Writer
{
    private $currentIndent = 0;
    private $indentString = "    ";
    private $inPhpDocComment = false;
    private $text;

    function Text()
    {
        return $this->text;
    }
    function StartPhp()
    {
        $this->AddLine("<?php");
    }
    function StartDocComment()
    {
        $this->AddLine("/**");
        $this->inPhpDocComment = true;
    }

    function EndDocComment()
    {
        $this->AddLine(" */");
        
        $this->inPhpDocComment = false;
    }
    function AddComment($line)
    {
        $this->AddLine('// ' . $line);
    }
    function AddArray($varDefinition, array $items = array())
    {
        $line = $varDefinition . ' = array(' . $this->GetArrayItemsString($items) . ')';
        $this->AddCommand($line);
    }
    private function GetArrayItemsString(array $arrayItems = array())
    {
        $result = '';
        $idx = 1;
        foreach ($arrayItems as $key=>$value)
        {
            $separator = ', ';
            if ($idx == count($arrayItems))
                $separator = '';
    
            if ($key !== null)
                $result  .= $key . '=>' . $value . $separator;
            else
                $result  .=  $value . $separator;
            ++$idx;
        }
        return $result;
    }
    
    function Start_Do()
    {
        $line = 'do';
        $this->AddLine($line);
        $this->StartBlock();
    }
    
    function End_Do($condition)
    {
        $line = 'while (' . $condition .');';
        $this->AddLine();
    }

    function StartWhile($condition)
    {
        $line = 'while (' . $condition . ')';
        $this->AddLine($line);
        $this->StartBlock();
    }
    
    function End_While()
    {
        $this->EndBlock();
    }
    function Start_If($condition)
    {
        $line = 'if (' . $condition . ')';
        $this->AddLine($line);
        $this->StartBlock();
    }
    function Add_Else()
    {
        $this->EndBlock();
        $this->AddLine('else');
        $this->StartBlock();
    }
    
    function Add_Else_If($condition)
    {
        $this->EndBlock();
        $line = 'else if(' . $condition . ')';
        $this->AddLine($line);
        $this->StartBlock();
    }
    function End_If()
    {
        $this->EndBlock();
    }
    
    function AddCommand($command)
    {
        $this->AddLine($command . ';');
    }
    function AddDocComment($line, $atDecl = '')
    {
        if (!$this->inPhpDocComment)
            throw new \BadMethodCallException('Called AddDocComment without StartDocComment.');
        
        if ($atDecl)
            $line = '@' . $atDecl . ' ' . $line;

        $this->AddLine(' * ' . $line);
    }
    function EndPhp()
    {
        $this->AddLine("?>");
    }
    
    function StartClass($declaration, $extends = '', $implements = array())
    {
        $line = 'class ' . $declaration;
        if ($extends)
            $line .= ' extends ' . $extends;

        if (count ($implements))
            $line .= ' implements ' . join(', ', $implements);
        
        $this->AddLine($line);
        $this->StartBlock();
    }
    
    function EndClass()
    {
        $this->EndBlock();
    }
    function StartFunction($declaration, array $params = array())
    {
        $line = $declaration . '(' . join(', ', $params) .')';
        $this->AddLine($line);
        $this->StartBlock();
    }
    
    function EndFunction()
    {
        $this->EndBlock();
    }
    
    function StartBlock()
    {
        $this->AddLine('{');
        $this->IncrementIndent();
    }

    function EndBlock()
    {
        $this->DecrementIndent();
        $this->AddLine('}');
    }
    
    
    private function AddLine($text = '')
    {
        $this->AddIndent();
        $this->AddText($text . "\r\n");
    }
    
    private function IncrementIndent()
    {
        
        ++$this->currentIndent;    
    }
    private function DecrementIndent()
    {
        $this->currentIndent = max(0, $this->currentIndent - 1);    
    }
    private function AddIndent()
    {
        for ($idx = 0; $idx < $this->currentIndent; ++$idx)
            $this->text .= $this->indentString;
    }
    
    private function AddText($text)
    {
        $this->text = $this->text . $text;
    }
    
    public function AddNamespace($namespace)
    {
        $this->AddCommand("namespace $namespace");
    }
    
    
    public function AddNamespaceArray(array $namespaceParts)
    {
        $this->AddNamespace(join('\\', $namespaceParts));
    }
    /**
     * Adds a namespace "use' command with separate namespace parts provided as array
     * @param array $namespaceParts The parts of the namespace separated by backslash
     * @param string $alias The namespace alias, can be omitted
     */
    function AddNamespaceArrayUse(array $namespaceParts, $alias = '')
    {
        $namespace = join('\\', $namespaceParts);
        
        
        $this->AddNamespaceUse($namespace, $alias);
    }
    /**
     * Adds a namespace 'use' command
     * @param string $namespace The fully qualified namespace name
     * @param string $alias The namespace alias, can be omitted
     */
    function AddNamespaceUse($namespace, $alias = '')
    {
        $command = 'use ' . $namespace;
        if ($alias)
            $command .= ' as ' . $alias;
        
        $this->AddCommand($command);
    }
    
    function StartTry()
    {
        $this->AddLine('try');
        $this->StartBlock();
    }
    function EndBlockBeginCatch($exceptionType, $exception)
    {
        $this->EndBlock();
        $this->AddLine('catch(' . $exceptionType . ' '. $exception .')');
        $this->StartBlock();
    }
    
    function EndCatch()
    {
        $this->EndBlock();
    }
    
}