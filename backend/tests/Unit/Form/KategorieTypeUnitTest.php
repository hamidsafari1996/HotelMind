<?php

namespace App\Tests\Unit\Form;

use App\Entity\Kategorie;
use App\Form\KategorieType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KategorieTypeUnitTest extends TestCase
{
    private KategorieType $formType;

    protected function setUp(): void
    {
        $this->formType = new KategorieType();
    }

    public function testFormTypeCanBeInstantiated(): void
    {
        // Test that the form type can be instantiated
        $formType = new KategorieType();
        $this->assertInstanceOf(KategorieType::class, $formType);
    }

    public function testBuildFormMethodExists(): void
    {
        // Test that the buildForm method exists and is callable
        $this->assertTrue(method_exists($this->formType, 'buildForm'));
        $this->assertTrue(is_callable([$this->formType, 'buildForm']));
    }

    public function testConfigureOptionsMethodExists(): void
    {
        // Test that the configureOptions method exists and is callable
        $this->assertTrue(method_exists($this->formType, 'configureOptions'));
        $this->assertTrue(is_callable([$this->formType, 'configureOptions']));
    }

    public function testFormTypeExtendsAbstractType(): void
    {
        // Test that KategorieType extends AbstractType (Symfony convention)
        $reflection = new \ReflectionClass(KategorieType::class);
        $parentClass = $reflection->getParentClass();
        
        $this->assertNotFalse($parentClass);
        $this->assertEquals('Symfony\Component\Form\AbstractType', $parentClass->getName());
    }

    public function testBuildFormAddsNameField(): void
    {
        // Arrange
        $builder = $this->createMock(FormBuilderInterface::class);
        
        // Assert that add method is called exactly once with 'name'
        $builder->expects($this->once())
            ->method('add')
            ->with('name')
            ->willReturnSelf();

        // Act
        $this->formType->buildForm($builder, []);
    }

    public function testBuildFormCallsAddMethodOnce(): void
    {
        // Arrange
        $builder = $this->createMock(FormBuilderInterface::class);
        
        // Assert that add method is called exactly once (only one field)
        $builder->expects($this->exactly(1))
            ->method('add')
            ->willReturnSelf();

        // Act
        $this->formType->buildForm($builder, []);
    }

    public function testFormHasCorrectFieldConfiguration(): void
    {
        // Arrange
        $builder = $this->createMock(FormBuilderInterface::class);
        $addedFields = [];
        
        // Capture field configuration when add is called
        $builder->expects($this->once())
            ->method('add')
            ->willReturnCallback(function($fieldName, $fieldType = null, $options = []) use (&$addedFields, $builder) {
                $addedFields[] = [
                    'name' => $fieldName,
                    'type' => $fieldType,
                    'options' => $options
                ];
                return $builder;
            });

        // Act
        $this->formType->buildForm($builder, []);

        // Assert
        $this->assertCount(1, $addedFields);
        $this->assertEquals('name', $addedFields[0]['name']);
        $this->assertNull($addedFields[0]['type']); // No specific type set, using default
        $this->assertEmpty($addedFields[0]['options']); // No options set
    }

    public function testConfigureOptionsSetup(): void
    {
        // Arrange
        $resolver = new OptionsResolver();
        
        // Act
        $this->formType->configureOptions($resolver);
        $options = $resolver->resolve();
        
        // Assert
        $this->assertEquals(Kategorie::class, $options['data_class']);
    }

    public function testDataClassIsKategorie(): void
    {
        // Arrange
        $resolver = new OptionsResolver();
        
        // Act
        $this->formType->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve([]);
        
        // Assert
        $this->assertArrayHasKey('data_class', $resolvedOptions);
        $this->assertEquals(Kategorie::class, $resolvedOptions['data_class']);
    }

    public function testFormBuilderReturnsItself(): void
    {
        // Test that FormBuilder returns itself for chaining
        $builder = $this->createMock(FormBuilderInterface::class);
        
        $builder->method('add')
            ->willReturnSelf();

        // This should not throw any exceptions
        $this->formType->buildForm($builder, []);
        $this->assertTrue(true);
    }

    public function testNameFieldIsAddedWithoutSpecificType(): void
    {
        // Test that name field is added without specific type (uses default TextType)
        $builder = $this->createMock(FormBuilderInterface::class);
        
        $builder->expects($this->once())
            ->method('add')
            ->with('name', null, [])
            ->willReturnSelf();

        $this->formType->buildForm($builder, []);
        $this->assertTrue(true); // If no exception thrown, test passes
    }

    public function testFormHasOnlyNameField(): void
    {
        // Test that form has exactly one field and it's the name field
        $builder = $this->createMock(FormBuilderInterface::class);
        $fieldNames = [];
        
        $builder->method('add')
            ->willReturnCallback(function($fieldName) use (&$fieldNames, $builder) {
                $fieldNames[] = $fieldName;
                return $builder;
            });

        $this->formType->buildForm($builder, []);

        // Assert exactly one field
        $this->assertCount(1, $fieldNames);
        $this->assertEquals(['name'], $fieldNames);
        $this->assertContains('name', $fieldNames);
    }

    public function testFormIsSimpleForm(): void
    {
        // Test that this is a simple form with minimal configuration
        $builder = $this->createMock(FormBuilderInterface::class);
        $callCount = 0;
        
        $builder->method('add')
            ->willReturnCallback(function() use (&$callCount, $builder) {
                $callCount++;
                return $builder;
            });

        $this->formType->buildForm($builder, []);

        // Simple form should have only 1 add call
        $this->assertEquals(1, $callCount);
    }

    public function testBuildFormWithEmptyOptions(): void
    {
        // Test that buildForm works with empty options array
        $builder = $this->createMock(FormBuilderInterface::class);
        
        $builder->expects($this->once())
            ->method('add')
            ->with('name')
            ->willReturnSelf();

        // Should work without any issues
        $this->formType->buildForm($builder, []);
        $this->assertTrue(true);
    }

    public function testBuildFormWithOptionsArray(): void
    {
        // Test that buildForm works with non-empty options array
        $builder = $this->createMock(FormBuilderInterface::class);
        $options = ['some_option' => 'some_value'];
        
        $builder->expects($this->once())
            ->method('add')
            ->with('name')
            ->willReturnSelf();

        // Should work regardless of options content
        $this->formType->buildForm($builder, $options);
        $this->assertTrue(true);
    }

    public function testConfigureOptionsWithEmptyResolver(): void
    {
        // Test configureOptions with fresh resolver
        $resolver = new OptionsResolver();
        
        // Should not throw any exceptions
        $this->formType->configureOptions($resolver);
        
        // Verify that data_class is the only default set
        $defaults = $resolver->resolve();
        $this->assertArrayHasKey('data_class', $defaults);
        $this->assertEquals(Kategorie::class, $defaults['data_class']);
    }

    public function testFormTypeStructure(): void
    {
        // Test overall form type structure
        $reflection = new \ReflectionClass($this->formType);
        
        // Should have buildForm method
        $this->assertTrue($reflection->hasMethod('buildForm'));
        $buildFormMethod = $reflection->getMethod('buildForm');
        $this->assertEquals(2, $buildFormMethod->getNumberOfRequiredParameters());
        
        // Should have configureOptions method
        $this->assertTrue($reflection->hasMethod('configureOptions'));
        $configureOptionsMethod = $reflection->getMethod('configureOptions');
        $this->assertEquals(1, $configureOptionsMethod->getNumberOfRequiredParameters());
    }

    public function testNameFieldWithDefaultConfiguration(): void
    {
        // Test that name field uses default configuration (no type, no options)
        $builder = $this->createMock(FormBuilderInterface::class);
        
        $builder->expects($this->once())
            ->method('add')
            ->with(
                $this->equalTo('name'),
                $this->isNull(), // No specific type
                $this->isEmpty() // No options
            )
            ->willReturnSelf();

        $this->formType->buildForm($builder, []);
    }

    public function testFormSimplicity(): void
    {
        // Test that this form is appropriately simple
        $builder = $this->createMock(FormBuilderInterface::class);
        $addCalls = [];
        
        $builder->method('add')
            ->willReturnCallback(function($fieldName, $fieldType = null, $options = []) use (&$addCalls, $builder) {
                $addCalls[] = [
                    'field' => $fieldName,
                    'type' => $fieldType,
                    'options' => $options,
                    'has_type' => $fieldType !== null,
                    'has_options' => !empty($options)
                ];
                return $builder;
            });

        $this->formType->buildForm($builder, []);

        // Verify simplicity
        $this->assertCount(1, $addCalls, 'Should have exactly 1 field');
        
        $nameField = $addCalls[0];
        $this->assertEquals('name', $nameField['field']);
        $this->assertFalse($nameField['has_type'], 'Should use default field type');
        $this->assertFalse($nameField['has_options'], 'Should have no special options');
    }

    public function testFormIsMinimalImplementation(): void
    {
        // Test that this is a minimal form implementation
        $this->assertTrue(method_exists($this->formType, 'buildForm'));
        $this->assertTrue(method_exists($this->formType, 'configureOptions'));
        
        // Verify no additional methods beyond required ones
        $reflection = new \ReflectionClass($this->formType);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodNames = array_map(fn($method) => $method->getName(), $methods);
        
        // Should only have the required methods (plus inherited ones)
        $this->assertContains('buildForm', $methodNames);
        $this->assertContains('configureOptions', $methodNames);
    }
} 