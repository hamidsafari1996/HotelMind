<?php

namespace App\Tests\Unit\Form;

use App\Entity\Hotel;
use App\Entity\Kategorie;
use App\Form\HotelType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelTypeUnitTest extends TestCase
{
    private HotelType $formType;

    protected function setUp(): void
    {
        $this->formType = new HotelType();
    }

    public function testFormTypeCanBeInstantiated(): void
    {
        // Test that the form type can be instantiated
        $formType = new HotelType();
        $this->assertInstanceOf(HotelType::class, $formType);
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

    public function testConfigureOptionsSetup(): void
    {
        // Arrange
        $resolver = new OptionsResolver();
        
        // Act
        $this->formType->configureOptions($resolver);
        $options = $resolver->resolve();
        
        // Assert
        $this->assertEquals(Hotel::class, $options['data_class']);
    }

    public function testDataClassIsHotel(): void
    {
        // Arrange
        $resolver = new OptionsResolver();
        
        // Act
        $this->formType->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve([]);
        
        // Assert
        $this->assertArrayHasKey('data_class', $resolvedOptions);
        $this->assertEquals(Hotel::class, $resolvedOptions['data_class']);
    }

    public function testFormTypeExtendsAbstractType(): void
    {
        // Test that HotelType extends AbstractType (Symfony convention)
        $reflection = new \ReflectionClass(HotelType::class);
        $parentClass = $reflection->getParentClass();
        
        $this->assertNotFalse($parentClass);
        $this->assertEquals('Symfony\Component\Form\AbstractType', $parentClass->getName());
    }

    public function testBuildFormCallsAddMethodMultipleTimes(): void
    {
        // Arrange
        $builder = $this->createMock(FormBuilderInterface::class);
        
        // Assert that add method is called exactly 12 times (one for each field)
        $builder->expects($this->exactly(12))
            ->method('add')
            ->willReturnSelf();

        // Act
        $this->formType->buildForm($builder, []);
    }

    public function testFormHasCorrectFieldsInOrder(): void
    {
        // Arrange
        $builder = $this->createMock(FormBuilderInterface::class);
        $fieldNames = [];
        
        // Capture field names when add is called
        $builder->expects($this->exactly(12))
            ->method('add')
            ->willReturnCallback(function($fieldName) use (&$fieldNames, $builder) {
                $fieldNames[] = $fieldName;
                return $builder;
            });

        // Act
        $this->formType->buildForm($builder, []);

        // Assert expected field order
        $expectedFields = [
            'title', 'location', 'kategorie', 'image', 'rating', 
            'stars', 'price', 'days', 'person', 'info', 
            'description', 'created_at'
        ];
        
        $this->assertEquals($expectedFields, $fieldNames);
        $this->assertCount(12, $fieldNames);
    }

    public function testFormFieldTypesCorrectlySet(): void
    {
        // Test multiple field configurations in one test to avoid conflicts
        $builder = $this->createMock(FormBuilderInterface::class);
        $addCalls = [];
        
        $builder->expects($this->exactly(12))
            ->method('add')
            ->willReturnCallback(function($fieldName, $fieldType = null, $options = []) use (&$addCalls, $builder) {
                $addCalls[] = ['name' => $fieldName, 'type' => $fieldType, 'options' => $options];
                return $builder;
            });

        $this->formType->buildForm($builder, []);

        // Verify specific field configurations
        $this->assertCount(12, $addCalls);
        
        // Check kategorie field
        $kategorieCall = array_filter($addCalls, fn($call) => $call['name'] === 'kategorie');
        $kategorieCall = reset($kategorieCall);
        $this->assertEquals(EntityType::class, $kategorieCall['type']);
        $this->assertEquals(Kategorie::class, $kategorieCall['options']['class']);
        $this->assertEquals('name', $kategorieCall['options']['choice_label']);
        
        // Check image field
        $imageCall = array_filter($addCalls, fn($call) => $call['name'] === 'image');
        $imageCall = reset($imageCall);
        $this->assertEquals(FileType::class, $imageCall['type']);
        $this->assertFalse($imageCall['options']['mapped']);
        $this->assertFalse($imageCall['options']['required']);
        $this->assertEquals('image/*', $imageCall['options']['attr']['accept']);
        
        // Check rating field
        $ratingCall = array_filter($addCalls, fn($call) => $call['name'] === 'rating');
        $ratingCall = reset($ratingCall);
        $this->assertEquals(NumberType::class, $ratingCall['type']);
        $this->assertEquals('Rating', $ratingCall['options']['label']);
        $this->assertFalse($ratingCall['options']['required']);
        $this->assertEquals(1, $ratingCall['options']['scale']);
        
        // Check stars field
        $starsCall = array_filter($addCalls, fn($call) => $call['name'] === 'stars');
        $starsCall = reset($starsCall);
        $this->assertEquals(IntegerType::class, $starsCall['type']);
        $this->assertEquals('Hotel Stars', $starsCall['options']['label']);
        $this->assertFalse($starsCall['options']['required']);
        
        // Check created_at field
        $createdAtCall = array_filter($addCalls, fn($call) => $call['name'] === 'created_at');
        $createdAtCall = reset($createdAtCall);
        $this->assertNull($createdAtCall['type']);
        $this->assertEquals('single_text', $createdAtCall['options']['widget']);
    }

    public function testAllExpectedFieldsArePresent(): void
    {
        // Test that all expected fields are added
        $builder = $this->createMock(FormBuilderInterface::class);
        $fieldNames = [];
        
        $builder->method('add')
            ->willReturnCallback(function($fieldName) use (&$fieldNames, $builder) {
                $fieldNames[] = $fieldName;
                return $builder;
            });

        $this->formType->buildForm($builder, []);

        $expectedFields = [
            'title', 'location', 'kategorie', 'image', 'rating', 
            'stars', 'price', 'days', 'person', 'info', 
            'description', 'created_at'
        ];
        
        foreach ($expectedFields as $expectedField) {
            $this->assertContains($expectedField, $fieldNames, "Field '{$expectedField}' should be present in the form");
        }
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

    public function testFormContainsRequiredAndOptionalFields(): void
    {
        // Test that the form has both required and optional fields
        $builder = $this->createMock(FormBuilderInterface::class);
        $callsWithRequiredFalse = 0;
        $callsWithoutRequiredOption = 0;
        
        $builder->method('add')
            ->willReturnCallback(function($fieldName, $fieldType = null, $options = []) use (&$callsWithRequiredFalse, &$callsWithoutRequiredOption, $builder) {
                if (isset($options['required']) && $options['required'] === false) {
                    $callsWithRequiredFalse++;
                } elseif (!isset($options['required'])) {
                    $callsWithoutRequiredOption++;
                }
                return $builder;
            });

        $this->formType->buildForm($builder, []);

        // We expect some fields to be optional (rating, stars, image)
        $this->assertGreaterThan(0, $callsWithRequiredFalse, 'Should have some optional fields');
        // And some fields to use default required behavior
        $this->assertGreaterThan(0, $callsWithoutRequiredOption, 'Should have some fields with default required behavior');
    }

    public function testImageFieldSpecialConfiguration(): void
    {
        // Test that image field has special configuration (mapped => false)
        $builder = $this->createMock(FormBuilderInterface::class);
        $imageFieldFound = false;
        
        $builder->method('add')
            ->willReturnCallback(function($fieldName, $fieldType = null, $options = []) use (&$imageFieldFound, $builder) {
                if ($fieldName === 'image' && $fieldType === FileType::class) {
                    $imageFieldFound = true;
                    $this->assertFalse($options['mapped'], 'Image field should not be mapped');
                    $this->assertFalse($options['required'], 'Image field should not be required');
                    $this->assertEquals('image/*', $options['attr']['accept'], 'Image field should accept image files');
                }
                return $builder;
            });

        $this->formType->buildForm($builder, []);
        $this->assertTrue($imageFieldFound, 'Image field should be present and configured correctly');
    }

    public function testRatingFieldHasValidationConstraints(): void
    {
        // Test that rating field has proper validation constraints in attributes
        $builder = $this->createMock(FormBuilderInterface::class);
        $ratingFieldFound = false;
        
        $builder->method('add')
            ->willReturnCallback(function($fieldName, $fieldType = null, $options = []) use (&$ratingFieldFound, $builder) {
                if ($fieldName === 'rating' && $fieldType === NumberType::class) {
                    $ratingFieldFound = true;
                    $this->assertEquals(0, $options['attr']['min'], 'Rating min should be 0');
                    $this->assertEquals(10, $options['attr']['max'], 'Rating max should be 10');
                    $this->assertEquals(0.1, $options['attr']['step'], 'Rating step should be 0.1');
                }
                return $builder;
            });

        $this->formType->buildForm($builder, []);
        $this->assertTrue($ratingFieldFound, 'Rating field should be present and configured correctly');
    }

    public function testStarsFieldHasValidationConstraints(): void
    {
        // Test that stars field has proper validation constraints in attributes
        $builder = $this->createMock(FormBuilderInterface::class);
        $starsFieldFound = false;
        
        $builder->method('add')
            ->willReturnCallback(function($fieldName, $fieldType = null, $options = []) use (&$starsFieldFound, $builder) {
                if ($fieldName === 'stars' && $fieldType === IntegerType::class) {
                    $starsFieldFound = true;
                    $this->assertEquals(1, $options['attr']['min'], 'Stars min should be 1');
                    $this->assertEquals(5, $options['attr']['max'], 'Stars max should be 5');
                }
                return $builder;
            });

        $this->formType->buildForm($builder, []);
        $this->assertTrue($starsFieldFound, 'Stars field should be present and configured correctly');
    }
} 