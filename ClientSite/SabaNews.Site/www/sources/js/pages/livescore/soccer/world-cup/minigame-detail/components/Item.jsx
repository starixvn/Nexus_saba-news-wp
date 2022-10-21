import React, { PureComponent } from 'react';
import { getVoteResult } from '../../common.functions';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';
import moment from 'moment';
import LangHelper from '@jsroot/common/langHelper';

export default class Iteam extends PureComponent {
    constructor(props) {
        super(props);
    }

    getTotalScore = () => {
        const { matches, votes } = this.props;
        let totalScore = 0;
        votes.map(vote => {
            const match = matches.find(item => item.matchId === vote.matchId);
            if(match) {
                const result = getVoteResult(match, vote);
                totalScore += result !== null ? result.score : 0;
            }
        });
        return totalScore;
    };

    getStageStatus = () => {
        const { matches } = this.props;
        const firstMatch = matches[0];
        const lastMatch = matches[matches.length - 1];
        const startTime = DateTimeService.convertFromUTC(firstMatch.startTime);
        const lastTime = DateTimeService.convertFromUTC(lastMatch.startTime);
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        const startMinutes = startTime.diff(currentTime, 'minutes');
        const lastMinutes = lastTime.diff(currentTime, 'minutes');
        let status = '';
        if(startMinutes > 60) {
            status = LangHelper.getResource('NotStarted');
        } else if(startMinutes <= 60 && startMinutes > 30) {
            status = LangHelper.getResource('Upcomming');
        } else if( startMinutes <= 30) {
            status = LangHelper.getResource('Inprogress');
        }
        if(lastMinutes <= 30) {
            status = LangHelper.getResource('Finish');
        } else if(lastMinutes <= 60) {
            status = LangHelper.getResource('Ending');
        }
        return status;
    };

    handleViewDetail = event => {
        const url = Site.resolveClientUrl(`world-cup-2022/su-kien-du-doan-hay-qua-lien-tay/chi-tiet/giai-doan/${this.props.stage}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    render() {
        const { stage } = this.props;
        return (
                <tr>
                    <td>{ stage }</td>
                    <td>Giai đoạn { stage }</td>
                    <td>{this.getStageStatus()}</td>
                    <td>
                        <span>{this.getTotalScore()}</span>
                        <span onClick={this.handleViewDetail}>-Xem</span>
                    </td>
                </tr>
        );
    }
}