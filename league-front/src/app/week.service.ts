import {Injectable} from '@angular/core';
import {Game, Prediction} from './week';

@Injectable({
    providedIn: 'root'
})
export class WeekService {

    url = 'http://localhost/api/results/';
    weekUrl = 'http://localhost/api/week/';
    predictionUrl = 'http://localhost/api/predict/';
    resetUrl = 'http://localhost/api/reset/';
    defaultWeek = -1;
    currentWeek = this.defaultWeek;
    maxWeek = this.defaultWeek;



    constructor() {
    }

    async getWeek(id: number): Promise<Game[]> {
        const response = await fetch(this.url + id);
        return response.json();
    }

    async getCurrentWeek(): Promise<number> {
        if (this.currentWeek == this.defaultWeek) {
            const response = await fetch(this.weekUrl);
            const data = await response.json();
            this.currentWeek = data.current_week;
            this.maxWeek = data.max_week;
        }
        return Promise.resolve(this.currentWeek);
    }

    async getMaxWeek(): Promise<number> {
        if (this.maxWeek == this.defaultWeek) {
            const response = await fetch(this.weekUrl);
            const data = await response.json();
            this.currentWeek = data.current_week;
            this.maxWeek = data.max_week;
        }
        return Promise.resolve(this.maxWeek);
    }

    async playWeek(id: number): Promise<Game[]> {
        const formData = new FormData();
        formData.append('week_id', id.toString());

        let response = await fetch(this.url, {
            method: 'POST',
            body: formData
        });
        this.currentWeek = this.defaultWeek;
        return await response.json();
    }

    async getPrediction(): Promise<Prediction[]> {
        const response = await fetch(this.predictionUrl);
        return response.json();
    }

    async resetChampionship() {
        const response = await fetch(this.resetUrl, {
            method: 'POST',
        });
        this.currentWeek = this.defaultWeek;
        return response.json();
    }

}
