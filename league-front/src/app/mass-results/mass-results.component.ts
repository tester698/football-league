import {Component, EventEmitter, Input, SimpleChanges, OnChanges} from '@angular/core';
import {ButtonModule} from "primeng/button";
import {SharedModule} from "primeng/api";
import {TableModule} from "primeng/table";
import {WeekComponent} from "../week/week.component";
import {Game} from "../week";


@Component({
  selector: 'app-mass-results',
  standalone: true,
  imports: [
    ButtonModule,
    SharedModule,
    TableModule,
    WeekComponent
  ],
  templateUrl: './mass-results.component.html',
  styleUrl: './mass-results.component.scss'
})
export class MassResultsComponent implements OnChanges{
  @Input() gamesMass: Game[] = [];
  @Input() gamesReset_2: number = 0;
  games: Game[] = [];

  ngOnChanges(changes: SimpleChanges) {
    if (changes['gamesMass'] && changes['gamesMass'].currentValue) {
      this.updateMassResults();
    }
    if (changes['gamesReset_2'] ) {
       this.resetState();
    }
  }

  resetState() {
        if (this.gamesReset_2) {
            this.games = [];
        }
       this.gamesReset_2 = 0;
    }

  updateMassResults() {
    this.games = [...this.games, ...this.gamesMass];
  }
}
