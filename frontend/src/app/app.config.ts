import { ApplicationConfig, importProvidersFrom } from '@angular/core';
import { provideRouter } from '@angular/router';
import { provideClientHydration } from '@angular/platform-browser';
import { provideAnimations } from '@angular/platform-browser/animations';
import { provideHttpClient, withFetch } from '@angular/common/http';
import { HammerModule } from '@angular/platform-browser';
import { NgxsModule } from '@ngxs/store';
import { AuthModule } from '@common/auth/auth.module';
import { AccountSettingsModule } from '@common/account-settings/account-settings.module';
import { PagesModule } from '@common/pages/shared/pages.module';
import { CookieNoticeModule } from '@common/gdpr/cookie-notice/cookie-notice.module';
import { ContactPageModule } from '@common/contact/contact-page.module';
import { LoadingIndicatorModule } from '@common/core/ui/loading-indicator/loading-indicator.module';
import { MatSnackBarModule } from '@angular/material/snack-bar';
import { SiteModule } from './site/site.module';
import { CORE_PROVIDERS } from '@common/core/core-providers';
import { Bootstrapper } from '@common/core/bootstrapper.service';
import { AppBootstrapperService } from './app-bootstrapper.service';
import { UrlGeneratorService } from '@common/core/services/url-generator.service';
import { AppUrlGeneratorService } from './app-url-generator.service';
import { APP_CONFIG } from '@common/core/config/app-config';
import { MTDB_CONFIG } from './mtdb-config';

export const appConfig: ApplicationConfig = {
  providers: [
    provideRouter([]),
    provideClientHydration(),
    provideAnimations(),
    provideHttpClient(withFetch()),
    importProvidersFrom(
      HammerModule,
      NgxsModule.forRoot([], {developmentMode: false}),
      AuthModule,
      AccountSettingsModule,
      PagesModule,
      CookieNoticeModule,
      ContactPageModule,
      LoadingIndicatorModule,
      SiteModule,
      MatSnackBarModule
    ),
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
  ]
};