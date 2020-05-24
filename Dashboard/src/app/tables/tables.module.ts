import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TablesComponent } from './tables.component';

import { TablesRoutingModule } from './tables-routing/tables-routing.module';
import { AnunciosComponent } from './anuncios/anuncios.component';
import { ConcursosComponent } from './concursos/concursos.component';
import { ProblemasComponent } from './problemas/problemas.component';
import { RankingComponent } from './ranking/ranking.component';

@NgModule({
  imports: [
    CommonModule,
    TablesRoutingModule
  ],
  declarations: [ TablesComponent, AnunciosComponent, ConcursosComponent, ProblemasComponent, RankingComponent ]
})
export class TablesModule { }
