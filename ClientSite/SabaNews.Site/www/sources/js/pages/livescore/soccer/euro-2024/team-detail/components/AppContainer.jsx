import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import LiveScore from './LiveScore';
import Players from './Players';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';


const tabs = [
    {
        id: 1,
        slug: 'lich-thi-dau',
        name: 'Lịch thi đấu'
    },
    {
        id: 2,
        slug: 'ket-qua',
        name: 'Kết quả'
    },
    {
        id: 3,
        slug: 'cau-thu',
        name: 'Cầu Thủ'
    },
    {
        id: 4,
        slug: 'tieu-su',
        name: 'Tiểu sử'
    }
];
export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            active: 1,
            titleNav : tabs[0].name
        },
        this.handleSelectTab = this
                .handleSelectTab
                .bind(this);
    }


    componentDidMount() {
        this.props.getTeamData(window.teamId);
    }

    handleSelectTab = key => {
        this.setState({
            active: key
        })
    }

    onChange = event => {
        const titleNav = tabs.find(item => item.id === parseInt(event.target.value))
        this.setState({
            active: parseInt(event.target.value),
            titleNav : titleNav.name
        });
    };




    renderContent = () => {
        const { preMatches, postMatches, players } = this.props;
        switch (this.state.active) {
            case 1:
                return (
                    <LiveScore matches={preMatches} />
                )
            case 2:
                return (
                    <LiveScore matches={postMatches} />
                )
            case 3:
                return (
                    <Players data={players} />
                )
            case 4:
                return (
                    <div>Tiểu Sử</div>
                )
            default:
                return (
                    <LiveScore matches={preMatches} />
                )
        }
    }

    render() {
        const { team } = this.props;
        if(team === null) {
            return null;
        }
        return (
            <div className="block-info-team">
                <div className="container">
                    <div className="info-main-team">
                        <div className="team-header">
                            <div className="name-team-detail">
                                <div className="team-detail-logo"><TeamIcon src={team.icon} /></div>
                                <div className="team-detail-name">{CommonFunctions.getTeamName(team)}</div>
                            </div>
                            <div className="team-menu-tabs">

                                <div className="nav-select-mb">
                                    <div className="name-round">{this.state.titleNav}</div>
                                    <select onChange={this.onChange} value={this.state.active}>
                                        {
                                            tabs.map(item => (
                                                <option value={item.id} key={item.id} >{item.name}</option>
                                            ))
                                        }
                                    </select>
                                </div>

                                <ul className="nav-team">
                                    {
                                        tabs.map(item => (
                                            <li key={item.id} onClick={() => this.handleSelectTab(item.id)} className={`nav-team-item ${this.state.active === item.id ? 'active' : ''}`}>
                                                {item.name}
                                            </li>
                                        ))
                                    }
                                </ul>
                            </div>
                        </div>
                        <div className="team-body">
                            {this.renderContent()}
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { preMatches, postMatches, players, team } = state;
    return { preMatches, postMatches, players, team };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);