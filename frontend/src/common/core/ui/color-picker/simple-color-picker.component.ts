import { Component, Input, Output, EventEmitter, forwardRef } from '@angular/core';
import { ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';

@Component({
  selector: 'simple-color-picker',
  template: `
    <input 
      type="color" 
      [value]="value || '#000000'" 
      (input)="onColorChange($event)"
      class="color-input"
    />
  `,
  styles: [`
    .color-input {
      width: 50px;
      height: 30px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  `],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => SimpleColorPickerComponent),
      multi: true
    }
  ]
})
export class SimpleColorPickerComponent implements ControlValueAccessor {
  @Input() value: string = '#000000';
  @Output() colorChange = new EventEmitter<string>();

  private onChange = (value: string) => {};
  private onTouched = () => {};

  onColorChange(event: Event): void {
    const target = event.target as HTMLInputElement;
    this.value = target.value;
    this.onChange(this.value);
    this.colorChange.emit(this.value);
  }

  writeValue(value: string): void {
    this.value = value || '#000000';
  }

  registerOnChange(fn: (value: string) => void): void {
    this.onChange = fn;
  }

  registerOnTouched(fn: () => void): void {
    this.onTouched = fn;
  }
}