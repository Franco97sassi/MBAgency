import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NZ_I18N } from 'ng-zorro-antd/i18n';
import { en_US } from 'ng-zorro-antd/i18n';
import { registerLocaleData } from '@angular/common';
import en from '@angular/common/locales/en';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { IconsProviderModule } from './icons-provider.module';
import { NzLayoutModule } from 'ng-zorro-antd/layout';
import { NzMenuModule } from 'ng-zorro-antd/menu';

import { ThemeConstantService } from './shared/services/theme-constant.service';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { FullLayoutComponent } from './pages/layouts/full-layout/full-layout.component';
import { CommonLayoutComponent } from './pages/layouts/common-layout/common-layout.component';
import { NzMessageModule } from 'ng-zorro-antd/message';

import { HashLocationStrategy, LocationStrategy } from "@angular/common";

registerLocaleData(en);

@NgModule({
  declarations: [AppComponent, FullLayoutComponent, CommonLayoutComponent],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule,
    BrowserAnimationsModule,
    IconsProviderModule,
    NzLayoutModule,
    NzMenuModule,
    PerfectScrollbarModule,
    NzMessageModule,
  ],
  providers: [{ provide: NZ_I18N, useValue: en_US }, ThemeConstantService,  {provide : LocationStrategy , useClass: HashLocationStrategy}],
  bootstrap: [AppComponent],
})
export class AppModule {}