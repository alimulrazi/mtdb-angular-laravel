import {NgModule} from '@angular/core';
import {ColorpickerPanelComponent} from './colorpicker-panel.component';
import {SimpleColorPickerComponent} from './simple-color-picker.component';
import {OverlayModule} from '@angular/cdk/overlay';
import {MatIconModule} from '@angular/material/icon';
import {TranslationsModule} from '@common/core/translations/translations.module';
import {CommonModule} from '@angular/common';

@NgModule({
    imports: [
        CommonModule,
        // ColorPickerModule, // Temporarily disabled for Angular 18 compatibility
        OverlayModule,
        TranslationsModule,

        // material
        MatIconModule,
    ],
    declarations: [
        ColorpickerPanelComponent,
        SimpleColorPickerComponent,
    ],
    exports: [
        ColorpickerPanelComponent,
        SimpleColorPickerComponent,
    ],
})
export class BeColorPickerModule {
    static components = {
        panel: ColorpickerPanelComponent,
    };
}
