import {BrowserModule, HammerModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {AppComponent} from './app.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {RouterModule} from '@angular/router';
import {MTDB_CONFIG} from './mtdb-config';
import {AppRoutingModule} from './app-routing.module';
import {SiteModule} from './site/site.module';
import {NgxsModule} from '@ngxs/store';
import {AuthModule} from '@common/auth/auth.module';
import {AccountSettingsModule} from '@common/account-settings/account-settings.module';
import {APP_CONFIG} from '@common/core/config/app-config';
import {CommonModule} from '@angular/common';
import {HttpClientModule} from '@angular/common/http';
import {CookieNoticeModule} from '@common/gdpr/cookie-notice/cookie-notice.module';
import {ContactPageModule} from '@common/contact/contact-page.module';
import {MatSnackBarModule} from '@angular/material/snack-bar';
import {MatChipsModule} from '@angular/material/chips';
import {MatIconModule} from '@angular/material/icon';
import {MatDialogModule} from '@angular/material/dialog';
import {MatStepperModule} from '@angular/material/stepper';
import {MatButtonModule} from '@angular/material/button';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatInputModule} from '@angular/material/input';
import {MatSelectModule} from '@angular/material/select';
import {MatCheckboxModule} from '@angular/material/checkbox';
import {MatRadioModule} from '@angular/material/radio';
import {MatSlideToggleModule} from '@angular/material/slide-toggle';
import {MatMenuModule} from '@angular/material/menu';
import {MatToolbarModule} from '@angular/material/toolbar';
import {MatSidenavModule} from '@angular/material/sidenav';
import {MatListModule} from '@angular/material/list';
import {MatCardModule} from '@angular/material/card';
import {MatTabsModule} from '@angular/material/tabs';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import {MatProgressBarModule} from '@angular/material/progress-bar';
import {MatPaginatorModule} from '@angular/material/paginator';
import {MatSortModule} from '@angular/material/sort';
import {MatTableModule} from '@angular/material/table';
import {MatTooltipModule} from '@angular/material/tooltip';
import {MatExpansionModule} from '@angular/material/expansion';
import {MatDatepickerModule} from '@angular/material/datepicker';
import {MatNativeDateModule} from '@angular/material/core';
import {MatSliderModule} from '@angular/material/slider';
import {MatAutocompleteModule} from '@angular/material/autocomplete';
import {MatBadgeModule} from '@angular/material/badge';
import {MatBottomSheetModule} from '@angular/material/bottom-sheet';
import {MatButtonToggleModule} from '@angular/material/button-toggle';
import {MatDividerModule} from '@angular/material/divider';
import {MatGridListModule} from '@angular/material/grid-list';
import {MatRippleModule} from '@angular/material/core';
import {MatTreeModule} from '@angular/material/tree';
import {LoadingIndicatorModule} from '@common/core/ui/loading-indicator/loading-indicator.module';
import {CORE_PROVIDERS} from '@common/core/core-providers';
import {Bootstrapper} from '@common/core/bootstrapper.service';
import {AppBootstrapperService} from './app-bootstrapper.service';
import {PagesModule} from '@common/pages/shared/pages.module';
import {NotFoundRoutingModule} from '@common/pages/not-found-routing.module';
import {UrlGeneratorService} from '@common/core/services/url-generator.service';
import {AppUrlGeneratorService} from './app-url-generator.service';

@NgModule({
    declarations: [AppComponent],
    imports: [
        CommonModule,
        BrowserModule,
        BrowserAnimationsModule,
        HammerModule,
        HttpClientModule,
        RouterModule.forRoot([], {
            scrollPositionRestoration: 'top',
        }),
        AuthModule,
        AccountSettingsModule,
        AppRoutingModule,
        PagesModule,
        CookieNoticeModule,
        ContactPageModule,
        LoadingIndicatorModule,

        NgxsModule.forRoot([], {developmentMode: false}),

        // need to load these after "NgxsModule.forRoot"
        // as site module contains "NgxsModule.forFeature"
        SiteModule,
        NotFoundRoutingModule,

        // material
        MatSnackBarModule,
        MatChipsModule,
        MatIconModule,
        MatDialogModule,
        MatStepperModule,
        MatButtonModule,
        MatFormFieldModule,
        MatInputModule,
        MatSelectModule,
        MatCheckboxModule,
        MatRadioModule,
        MatSlideToggleModule,
        MatMenuModule,
        MatToolbarModule,
        MatSidenavModule,
        MatListModule,
        MatCardModule,
        MatTabsModule,
        MatProgressSpinnerModule,
        MatProgressBarModule,
        MatPaginatorModule,
        MatSortModule,
        MatTableModule,
        MatTooltipModule,
        MatExpansionModule,
        MatDatepickerModule,
        MatNativeDateModule,
        MatSliderModule,
        MatAutocompleteModule,
        MatBadgeModule,
        MatBottomSheetModule,
        MatButtonToggleModule,
        MatDividerModule,
        MatGridListModule,
        MatRippleModule,
        MatTreeModule,
    ],
    providers: [
        ...CORE_PROVIDERS,
        {
            provide: Bootstrapper,
            useClass: AppBootstrapperService,
        },
        {
            provide: UrlGeneratorService,
            useClass: AppUrlGeneratorService,
        },
        {
            provide: APP_CONFIG,
            useValue: MTDB_CONFIG,
            multi: true,
        },
    ],
    bootstrap: [AppComponent],
})
export class AppModule {}
