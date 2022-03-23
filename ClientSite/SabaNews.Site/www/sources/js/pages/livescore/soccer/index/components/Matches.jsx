import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import EventItem from './EventItem';
import { ActionCreators } from '../actions';

export class Matches extends Component {
    groupBy = (array, f) => {
        var groups = {};
        array.forEach(o => {
            var group = JSON.stringify(f(o));
            groups[group] = groups[group] || [];
            groups[group].push(o);
        });
        return Object.keys(groups).map(function (group) {
            return { leagueId: group[1], leagueName: group[2], matches: groups[group] };
        })
    }

    render() {
        if (!this.props.matches || this.props.matches.length === 0) {
            return null;
        }

        const helper = {};
        const matches = this.props.matches.reduce((previousItem, currentItem) => {
            const key = currentItem.leagueId;
            if (!helper[key]) {
                helper[key] = { leagueId: currentItem.leagueId, leagueEn: currentItem.leagueEn, matches: [{ ...currentItem }] };
                previousItem.push(helper[key]);
            } else {
                helper[key].matches.push(currentItem);
            }

            return previousItem;
        }, []);

        return (
            <div className="events-grid">
                {
                    matches.map((league, index) => <EventItem key={index} league={league} />)
                }
            </div>
        );
    }
}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

export default connect(null, mapDispatchToProps)(Events);