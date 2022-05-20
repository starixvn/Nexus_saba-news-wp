import React, { PureComponent } from 'react';
import moment from 'moment';
import LangHelper from '@jsroot/common/langHelper';
import DateTimeService from '@jsroot/common/dateTimeService';
import { MatchStatus } from '@jsroot/common/constants';

export default class MatchStage extends PureComponent {
    constructor(props) {
        super(props);

        this.intervalId = null;

        this.state = {
            stage: '',
            className: ''
        };
    }

    componentDidMount() {
        this.getMatchStage();
    }

    componentWillUnmount() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }

    componentDidUpdate(prevProps) {
        if (prevProps.match !== this.props.match) {
            if (this.intervalId) {
                clearInterval(this.intervalId);
            }

            this.getMatchStage();
        }
    }

    getMatchStage = () => {
        const match = this.props.match;
        let className = '';
        let stage = '';
        switch (match.status) {
            case MatchStatus.NotStarted: //NOSONAR
                const startTime = DateTimeService.convertFromUTC(match.startTime);
                className = 'font-weight-bold';
                stage = DateTimeService.toShortTimeFormat(startTime);
                break;

            case MatchStatus.FirstHalf: //NOSONAR
                this.intervalId = setInterval(() => this.setState({ stage: this.getFirstHalfTimeMinutes(match) }), 1000 * 60);
                stage = this.getFirstHalfTimeMinutes(match);
                break;

            case MatchStatus.SecondHalf: //NOSONAR
                this.intervalId = setInterval(() => this.setState({ stage: this.getSecondHalfTimeMinutes(match) }), 1000 * 60);
                stage = this.getSecondHalfTimeMinutes(match);
                break;

            case MatchStatus.ExtraTime: //NOSONAR
                this.intervalId = setInterval(() => this.setState({ stage: this.getExtraTimeMinutes(match) }), 1000 * 60);
                stage = this.getExtraTimeMinutes(match);
                break;

            case MatchStatus.Intermission: //NOSONAR
                stage = LangHelper.getResource('Intermission');
                break;

            case MatchStatus.PenaltyShootout: //NOSONAR
                stage = LangHelper.getResource('Penalty');
                break;

            case MatchStatus.Delay: //NOSONAR
                stage = LangHelper.getResource('Delay');
                break;

            case MatchStatus.Interrupt: //NOSONAR
                stage = LangHelper.getResource('Interrupt');
                break;

            case MatchStatus.Postpone: //NOSONAR
                stage = LangHelper.getResource('Postponed');
                break;

            case MatchStatus.Cancel: //NOSONAR
                stage = LangHelper.getResource('Cancel');
                break;

            case MatchStatus.TBD: //NOSONAR
                stage = LangHelper.getResource('ToBeDecided');
                break;

            default:
                stage = LangHelper.getResource('Finish');
                break;
        }

        this.setState({ stage, className });
    };

    getFirstHalfTimeMinutes = match => {
        const startTime = match.footballStartTime
            ? moment(DateTimeService.convertFromUTC(match.footballStartTime))
            : moment(DateTimeService.convertFromUTC(match.startTime));
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        let minutes = currentTime.diff(startTime, 'minutes');
        minutes = minutes < 0 ? 0 : minutes;

        return minutes <= 45 ? `${minutes}'` : `45'+`;
    };

    getSecondHalfTimeMinutes = match => {
        const startTime = match.footballNextHalfStartTime
            ? moment(DateTimeService.convertFromUTC(match.footballNextHalfStartTime))
            : moment(DateTimeService.convertFromUTC(match.startTime)).add(1, 'hours');
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        let minutes = currentTime.diff(startTime, 'minutes');
        minutes = minutes < 0 ? 0 : minutes;

        return minutes <= 45 ? `${45 + minutes}'` : `90'+`;
    };

    getExtraTimeMinutes = match => {
        const startTime = match.footballAddStartTime
            ? moment(DateTimeService.convertFromUTC(match.footballAddStartTime))
            : moment(DateTimeService.convertFromUTC(match.startTime)).add(2, 'hours');
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        const minutes = currentTime.diff(startTime, 'minutes') + 90;
        return minutes <= 120 ? `${minutes}'` : `120'+`;
    };

    render() {
        const { stage, className } = this.state;
        return (
            <div className="soccer-match-stage"><span className={className}>{stage}</span></div>
        );
    }
}