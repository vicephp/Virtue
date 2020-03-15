<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;
use Virtue\Forms\FormRenderer\DOMDocumentRenderer;
use Virtue\Forms\FormRenderer\SimpleXMLRenderer;

class FormViewTest extends TestCase
{
    /** @var FormView */
    private $formView;

    protected function setUp()
    {
        parent::setUp();
        $this->formView = new FormView(new DOMDocumentRenderer());
    }

    public function testSelectElement()
    {
        $expected = '<select name="aSelectElement"/>';
        $this->assertEquals($expected, $this->formView->selectElement('aSelectElement', []));

        $expected = <<<HTML
<select name="aSelectElement">
  <option value="aValue" label="aLabel"/>
</select>
HTML;
        $this->assertEquals($expected, $this->formView->selectElement('aSelectElement', ['aLabel' => 'aValue']));

        $options = ['optLabel' => ['aLabel' => 'aValue', 'bLabel' => 'aValue']];
        $expected = <<<HTML
<select name="aSelectElement">
  <optgroup label="optLabel">
    <option value="aValue" label="aLabel"/>
    <option value="aValue" label="bLabel"/>
  </optgroup>
</select>
HTML;
        $this->assertEquals($expected, $this->formView->selectElement('aSelectElement', $options));
    }

    public function testButtonInput()
    {
        $expected = '<input type="button" name="aButton" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->buttonInput('aButton', ['class' => 'someCssClass']));
    }

    public function testCheckboxInput()
    {
        $expected = '<input type="checkbox" name="aCheckbox" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->checkboxInput('aCheckbox', ['class' => 'someCssClass']));
    }

    public function testColorInput()
    {
        $expected = '<input type="color" name="aColor" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->colorInput('aColor', ['class' => 'someCssClass']));
    }

    public function testDateInput()
    {
        $expected = '<input type="date" name="aDate" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->dateInput('aDate', ['class' => 'someCssClass']));
    }

    public function testDatetimeLocalInput()
    {
        $expected = '<input type="datetime-local" name="aDatetimeLocal" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->datetimeLocalInput('aDatetimeLocal', ['class' => 'someCssClass']));
    }

    public function testEmailInput()
    {
        $expected = '<input type="email" name="anEmail" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->emailInput('anEmail', ['class' => 'someCssClass']));
    }

    public function testFileInput()
    {
        $expected = '<input type="file" name="aFile" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->fileInput('aFile', ['class' => 'someCssClass']));
    }

    public function testHiddenInput()
    {
        $expected = '<input type="hidden" name="aHidden" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->hiddenInput('aHidden', ['class' => 'someCssClass']));
    }

    public function testImageInput()
    {
        $expected = '<input type="image" name="anImage" alt="anImage" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->imageInput('anImage', ['class' => 'someCssClass']));
    }

    public function testMonthInput()
    {
        $expected = '<input type="month" name="aMonth" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->monthInput('aMonth', ['class' => 'someCssClass']));
    }

    public function testNumberInput()
    {
        $expected = '<input type="number" name="aNumber" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->numberInput('aNumber', ['class' => 'someCssClass']));
    }

    public function testPasswordInput()
    {
        $expected = '<input type="password" name="aPassword" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->passwordInput('aPassword', ['class' => 'someCssClass']));
    }

    public function testRadioInput()
    {
        $expected = '<input type="radio" name="aRadioButton" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->radioInput('aRadioButton', ['class' => 'someCssClass']));
    }

    public function testRangeInput()
    {
        $expected = '<input type="range" name="aRange" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->rangeInput('aRange', ['class' => 'someCssClass']));
    }

    public function testResetInput()
    {
        $expected = '<input type="reset" name="aResetButton" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->resetInput('aResetButton', ['class' => 'someCssClass']));
    }

    public function testSearchInput()
    {
        $expected = '<input type="search" name="aSearchInput" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->searchInput('aSearchInput', ['class' => 'someCssClass']));
    }

    public function testSubmitInput()
    {
        $expected = '<input type="submit" name="aSubmitButton" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->submitInput('aSubmitButton', ['class' => 'someCssClass']));
    }

    public function testTelInput()
    {
        $expected = '<input type="tel" name="aTel" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->telInput('aTel', ['class' => 'someCssClass']));
    }

    public function testTextInput()
    {
        $expected = '<input type="text" name="aTextInput" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->textInput('aTextInput', ['class' => 'someCssClass']));
    }

    public function testTimeInput()
    {
        $expected = '<input type="time" name="aTime" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->timeInput('aTime', ['class' => 'someCssClass']));
    }

    public function testUrlInput()
    {
        $expected = '<input type="url" name="anUrl" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->urlInput('anUrl', ['class' => 'someCssClass']));
    }

    public function testWeekInput()
    {
        $expected = '<input type="week" name="aWeek" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->weekInput('aWeek', ['class' => 'someCssClass']));
    }

    public function testDatetimeInput()
    {
        $expected = '<input type="datetime" name="aDatetime" class="someCssClass"/>';
        $this->assertEquals($expected, $this->formView->datetimeInput('aDatetime', ['class' => 'someCssClass']));
    }
}
