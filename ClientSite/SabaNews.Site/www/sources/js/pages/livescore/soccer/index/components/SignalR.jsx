import React, { Component } from 'react';
import * as signalR from '@microsoft/signalr';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class SignalR extends Component {
    constructor(props) {
        super(props);

        this.connection = null;
    }

    componentDidMount() {
        const protocol = new signalR.JsonHubProtocol();
        const transport = signalR.HttpTransportType.WebSockets;
        const options = {
            transport,
            logMessageContent: false,
            skipNegotiation: true,
            logger: signalR.LogLevel.None
        };

        this.connection = new signalR.HubConnectionBuilder()
            .withUrl('/livescore/hubs', options)
            .withHubProtocol(protocol)
            .build();

        this.connection.on('UpdateLiveMatches', matches => {
            this.props.setLiveMatches(matches);
        });

        this.connection.onclose(async () => {
            await this.start();
        });

        let lockResolver;
        if (navigator && navigator.locks && navigator.locks.request) {
            const promise = new Promise(res => {
                lockResolver = res;
            });

            navigator.locks.request('livescore_lock_navigator', { mode: 'shared' }, () => {
                return promise;
            });
        }

        this.start();
    }

    componentWillUnmount() {
        this.connection.stop();
    }

    start = async () => {
        try {
            await this.connection.start().then(() => {
                console.log('SignalR connected!!!');
            });
        } catch (err) {
            console.log(err);

            setTimeout(() => {
                const { today, selectedDate } = this.props;
                if (window.isLivePage || today === selectedDate) {
                    this.props.getMatches(selectedDate);
                }

                this.start();
            }, 60 * 1000);
        }
    };

    render() {
        return <span />;
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { today, selectedDate } = state;
    return { today, selectedDate };
};

export default connect(mapStateToProps, mapDispatchToProps)(SignalR);