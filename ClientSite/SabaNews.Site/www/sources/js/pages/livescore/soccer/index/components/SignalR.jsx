import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { JsonHubProtocol, HubConnectionBuilder, HttpTransportType, LogLevel } from '@microsoft/signalr';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';

export class SignalR extends Component {
    constructor(props) {
        super(props);

        this.connection = null;
    }

    componentDidMount() {
        const protocol = new JsonHubProtocol();
        const transport = HttpTransportType.WebSockets;
        const options = {
            transport,
            logMessageContent: false,
            skipNegotiation: true,
            logger: LogLevel.None
        };

        this.connection = new HubConnectionBuilder()
            .withUrl('/livescore/hubs', options)
            .withHubProtocol(protocol)
            .build();

        this.connection.on('UpdateLiveMatches', matches => {
            this.props.setLiveMatches(matches);
        });

        this.connection.onclose(async () => {
            await this.start();
        });

        Site.lockNavigator();

        this.start();
    }

    componentWillUnmount() {
        this.connection.stop();
    }

    start = async () => {
        try {
            await this.connection.start().then(() => {
                console.log('SignalR connected!!!'); //NOSONAR
            });
        } catch (err) {
            console.log(err); //NOSONAR

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