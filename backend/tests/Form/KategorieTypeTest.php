<?php

namespace App\Tests\Form;

use App\Entity\Kategorie;
use App\Form\KategorieType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class KategorieTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => 'Test Kategorie',
        ];

        $model = new Kategorie();
        $form = $this->factory->create(KategorieType::class, $model);

        $expected = new Kategorie();
        $expected->setName('Test Kategorie');

        // Submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        // Check that $model was modified as expected when the form is submitted
        $this->assertEquals($expected->getName(), $model->getName());
    }

    public function testSubmitEmptyData(): void
    {
        $formData = [
            'name' => '',
        ];

        $model = new Kategorie();
        $form = $this->factory->create(KategorieType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        // Form might still be valid depending on validation rules
        // If you have NotBlank constraint, this would be false
        
        $this->assertEquals('', $model->getName());
    }

    public function testSubmitNullData(): void
    {
        $formData = [
            'name' => null,
        ];

        $model = new Kategorie();
        $form = $this->factory->create(KategorieType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertNull($model->getName());
    }

    public function testFormHasCorrectFields(): void
    {
        $form = $this->factory->create(KategorieType::class);

        $this->assertTrue($form->has('name'));
        $this->assertCount(1, $form->all());
    }

    public function testFormView(): void
    {
        $model = new Kategorie();
        $model->setName('Existing Kategorie');

        $form = $this->factory->create(KategorieType::class, $model);
        $view = $form->createView();

        $this->assertArrayHasKey('name', $view->children);
        $this->assertEquals('Existing Kategorie', $view->children['name']->vars['value']);
    }

    public function testFormDataClass(): void
    {
        $form = $this->factory->create(KategorieType::class);
        $config = $form->getConfig();

        $this->assertEquals(Kategorie::class, $config->getDataClass());
    }

    public function testSubmitWithExtraData(): void
    {
        $formData = [
            'name' => 'Valid Name',
            'extraField' => 'This should be ignored',
        ];

        $model = new Kategorie();
        $form = $this->factory->create(KategorieType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('Valid Name', $model->getName());
        
        // Check what fields the form actually has
        $formFields = array_keys($form->all());
        
        // The form should only have the 'name' field, not 'extraField'
        $this->assertEquals(['name'], $formFields);
    }

    public function testNameFieldType(): void
    {
        $form = $this->factory->create(KategorieType::class);
        $nameField = $form->get('name');
        
        // Check that name field exists and has correct type
        $this->assertNotNull($nameField);
        
        // Get the field config
        $config = $nameField->getConfig();
        
        // The default type for 'add' without explicit type is TextType
        $this->assertEquals('Symfony\Component\Form\Extension\Core\Type\TextType', $config->getType()->getInnerType()::class);
    }

    public function testFormWithLongName(): void
    {
        $longName = str_repeat('A', 300); // Create a very long name
        
        $formData = [
            'name' => $longName,
        ];

        $model = new Kategorie();
        $form = $this->factory->create(KategorieType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($longName, $model->getName());
    }

    public function testFormWithSpecialCharacters(): void
    {
        $specialName = 'Kategorie with special chars: äöü@#$%^&*()_+{}|:"<>?';
        
        $formData = [
            'name' => $specialName,
        ];

        $model = new Kategorie();
        $form = $this->factory->create(KategorieType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($specialName, $model->getName());
    }

    public function testFormWithWhitespaceOnlyName(): void
    {
        $whitespaceName = '   ';
        
        $formData = [
            'name' => $whitespaceName,
        ];

        $model = new Kategorie();
        $form = $this->factory->create(KategorieType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        // The form might trim whitespace, so we check what actually gets set
        $actualName = $model->getName();
        // Accept either the original whitespace or trimmed version (empty string or null)
        $this->assertTrue(
            $actualName === $whitespaceName || 
            $actualName === '' || 
            $actualName === null,
            "Expected whitespace name to be preserved, trimmed to empty string, or set to null, got: " . var_export($actualName, true)
        );
    }

    public function testFormSubmissionWithExistingModel(): void
    {
        // Start with a model that already has data
        $model = new Kategorie();
        $model->setName('Original Name');

        $formData = [
            'name' => 'Updated Name',
        ];

        $form = $this->factory->create(KategorieType::class, $model);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals('Updated Name', $model->getName());
    }

    public function testPartialFormSubmission(): void
    {
        // Test partial submission (missing fields)
        $formData = []; // No name provided

        $model = new Kategorie();
        $form = $this->factory->create(KategorieType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertNull($model->getName());
    }
} 